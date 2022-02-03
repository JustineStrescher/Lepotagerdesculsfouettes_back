<?php

namespace App\Controller\Api;

use App\Entity\Command;
use App\Entity\ProductCommand;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class CommandController extends AbstractController
{
    /**
     * Returns the detail of an command
     * @Route("/api/command/info/{id<\d+>}", name="api_command_info", methods={"GET"})
     */
    public function getDetailCommand(Command $command): Response
    {
        
        // 404 ?
        if ($command === null) {
            return $this->json(['error' => 'Commande non trouvé.'], Response::HTTP_NOT_FOUND);
        }
        return $this->json(
            $command,
            Response::HTTP_OK,
            [],
            [
                'groups' => [ 'command_info']
            ]
        );
    }

    /**
     * 
     * @Route("/api/client/basket", name="api_client_basket_post", methods={"POST"})
     */
    public function postBasket(Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator, ProductRepository $productRepository, MailerInterface $mailer)
    {
        // 20% de taxe sur les produits
        $taxe_rate = 20;

        //Récuperer le contenu JSON.
        /*
        "products": [
            {
              "id": 2,
              "quantity": "2"
            },
            {
              "id": 3,
              "quantity": "1"
            }
          ]
        */

        $jsonContent=$request->getContent();

        $productsArrayFromCart = json_decode($jsonContent);
        // on va d'abord créer la commande dans la table command, pour cela il nous faut l'utilisateur connecté.
        $user = $this->getUser();

        // une fois en possession de l'id de la commande, on pourra remplir product_command
        $commandArray = array(
            'num_fact' => '#Fact_' . str_pad($user->getId() . count($user->getCommands()), 6, "0", STR_PAD_LEFT),
            'status' => 'En cours',
            'total_ttc' => 0,
            'total_ht' => 0,
            'total_tva' => 0,
        );

        foreach($productsArrayFromCart as $thisProduct) {
            // récupération des infos du produit
            $product = $productRepository->find($thisProduct->id);
            $productArray = array();
            $productArray['unit_price'] = $product->getPrice();
            $productArray['ttc'] = $product->getPrice() * $thisProduct->quantity;
            $productArray['ht'] = $productArray['ttc'] * (1 - ($taxe_rate/100)); 
            $productArray['tva'] = $productArray['ttc'] - $productArray['ht'];
            $productArray['quantity'] = $thisProduct->quantity;
            $productArray['entity'] = $product;
            $productsArray[] = $productArray;

            // MAJ du tableau général de commande
            $commandArray['total_ttc'] += $productArray['ttc'];
            $commandArray['total_ht'] += $productArray['ht'];
            $commandArray['total_tva'] += $productArray['tva'];
        }

        // Création de l'entité que l'on complète avec les données calculées au dessus
        $command = new Command();
        $command->setUser($user);
        $command->setStatus($commandArray['status']);
        $command->setTotalHT($commandArray['total_ht']);
        $command->setTotalTTC($commandArray['total_ttc']);
        $command->setTotalTVA($commandArray['total_tva']);
        $command->setNumFact($commandArray['num_fact']);

        // Valider l'entité
        // @link : https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($command);
        // Y'a-t-il des erreurs ?
        if (count($errors) > 0) {
            // tableau de retour
            $errorsClean = [];
            // @Retourner des erreurs de validation propres
            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $errorsClean[$error->getPropertyPath()][] = $error->getMessage();
            };

            return $this->json($errorsClean, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // enregistrement des données dans la base
        $entityManager = $doctrine->getManager();
        $entityManager->persist($command);
        $entityManager->flush();

        // on a la commande en base de données, on va lui associer les produits du panier dont toutes les informations sont dans le tableau $productsArray
        foreach($productsArray as $thisProductArray) {
            $productCommand = new ProductCommand();
            $productCommand->setTotalTTC($thisProductArray['ttc']);
            $productCommand->setTotalTVA($thisProductArray['tva']);
            $productCommand->setTotalHT($thisProductArray['ht']);
            $productCommand->setQuantity($thisProductArray['quantity']);
            $productCommand->setProduct($thisProductArray['entity']);
            $productCommand->setUnitPrice($thisProductArray['unit_price']);
            $productCommand->setCommand($command);
            // ajout des produits à la commande, ce sera utile pour avoir l'info lors de l'envoi d'email
            $command->addProductCommand($productCommand);
            // Valider l'entité
            // @link : https://symfony.com/doc/current/validation.html#using-the-validator-service
            $errors = $validator->validate($productCommand);
            // Y'a-t-il des erreurs ?
            if (count($errors) > 0) {
                // tableau de retour
                $errorsClean = [];
                // @Retourner des erreurs de validation propres
                /** @var ConstraintViolation $error */
                foreach ($errors as $error) {
                    $errorsClean[$error->getPropertyPath()][] = $error->getMessage();
                };

                return $this->json($errorsClean, Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            // enregistrement des données dans la base
            $entityManager = $doctrine->getManager();
            $entityManager->persist($productCommand);
        }
        $entityManager->flush();
        
        // envoi d'un email pour l'administrateur
        $this->sendEmail($command, 'admin', $mailer);
        // envoi d'un email pour le client
        $this->sendEmail($command, 'customer', $mailer);

        // jusqu'ici tout va bien, on retourne un message de validation
        $output = ['message'=>'OK'];

        // On retourne la réponse adapté (201)
        //dd($user);
        return $this->json(
            //Le client modifié peut etre ajouté en retour
            $output,
            //Le status code : 201 CREATED
            //utilisation des constantes de classes
            Response::HTTP_CREATED
        );
    }

    /**
     * 
     * 
     * 
    */
    public function sendEmail($command, $recipient, $mailer): void
    {
        $admin_email = 'contact@lepotagerdesculsfouettes.fr';
        if ($recipient == 'customer') {
            $recipient_email = $command->getUser()->GetEmail();
            $subject = "Votre commande sur le site lepotagerdesculsfouettes.fr";
            $textHtml = "Bonjour, <br /> nous confirmons votre commande N° " . $command->getNumFact() . " sur notre site lepotagerdesculsfouettes.fr <br /> Cette commande sera à retirer à l'exploitation le jour habituel. <br /> Pour plus d'informations vous pouvez nous contacter par email : contact@lepotagerdesculsfouettes.fr <br /> Merci et à bientôt ;-)";
        } else {
            $recipient_email = $admin_email;
            $subject = "Une commande à été créée sur le site lepotagerdesculsfouettes.fr";
            $textHtml = "Bonjour administrateur, <br /> une commande a été enregistrée sur le site, pour un montant approximatif de " . $command->getTotalTTC() . " € et comportant " . count($command->getProductCommands()) ." références. Vous pouvez consulter le détail de cette commande depuis le back office du site";
        }
        

        $email = (new Email())
            ->from($admin_email)
            ->to($recipient_email)
            ->subject($subject)
            //->text('Sending emails is fun again!')
            ->html($textHtml);

        $mailer->send($email);
    }
}
