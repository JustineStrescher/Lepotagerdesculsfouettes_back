<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * Retourne la liste des commandes passées de l'utilisateur
     * @Route("/api/client/{id<\d+>}/commands", name="api_client_commands", methods={"GET"})
     */
    public function getClientIdCommands(UserRepository $userRepository, $id): Response
    {
        $commands=$userRepository->findByClientIdCommands($id);
       
        return $this->json(
            $commands,
            Response::HTTP_OK,
            [],
            [
                'groups' => [ 'client_commands']
            ]);
    }
    /**
     * Retourne le détails d'un utilisateur
     * @Route("/api/client/{id<\d+>}", name="api_client_get", methods={"GET"})
     */
    public function getClientId(UserRepository $userRepository, $id){
        $client=$userRepository->findByClientId($id);
       
        return $this->json(
            $client,
            Response::HTTP_OK,
            [],
            [
                'groups' => [ 'client_id']
            ]
        );

    }
     /**
     * Retourne les info du compte utilisateur
     * @Route("/api/client/info", name="api_client_info", methods={"GET"})
     */
    public function getItemClient( SerializerInterface $serializer, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();

        return $this->json(
            $user,
            Response::HTTP_OK,
            [],
            ['groups' => ['user_info', 'command_info']]
        );
    }

     /**
     * 
     * @Route("/api/login_check", name="api_login_check", methods={"GET"})
     */

     /**
     * sauvgarde les modification du compte utilisateur
     * @Route("/api/client/update", name="api_client_udpate", methods={"POST"})
     */
    public function udpateClient(Request $request, SerializerInterface $serializer, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher, ValidatorInterface $validator)
    {
        //Récuperer le contenu JSON
        $jsonContent=$request->getContent();

        $user = $this->getUser();

        //Mise à jour de l'entité User
        $user = $serializer->deserialize($jsonContent, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);
        $hashedPassword = $userPasswordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        //On sauvegarde l'entité
        $errors = $validator->validate($user);
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

        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // On retourne la réponse adapté (200, 204 ou 404)
        //dd($user);
        return $this->json(
            //Le client modifié peut etre ajouté en retour
            ['message' => 'update OK'],
            //Le status code : 200 OK
            //utilisation des constantes de classes
            Response::HTTP_OK
        );
    }

    /**
     * 
     * @Route("/api/client/register", name="api_client_register_post", methods={"POST"})
     */
    public function postClient(Request $request, SerializerInterface $serializer, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher, ValidatorInterface $validator)
    {
        //Récuperer le contenu JSON
        $jsonContent=$request->getContent();


        //Désérialiser (convertir) le JSON en entité Doctrine User
        $user = $serializer->deserialize($jsonContent, User::class, 'json');

        // on défini en dur le role de l'utilisateur qui s'inscrit, pour éviter qu'il puisse envoyer un 'ROLE_ADMIN' en POST et créer un compte admin
        $user->setRoles(["ROLE_USER"]);
        
        //Validé l'entité User
        $hashedPassword = $userPasswordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $errors = $validator->validate($user);
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
        //On sauvegarde l'entité
        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // On retourne la réponse adapté (201)
        //dd($user);
        return $this->json(
            //Le client modifié peut etre ajouté en retour
            $user,
            //Le status code : 201 CREATED
            //utilisation des constantes de classes
            Response::HTTP_CREATED,
            
            //Groups
            [
                'groups' => [ 'client_id']
            ]
        );
    }
}
