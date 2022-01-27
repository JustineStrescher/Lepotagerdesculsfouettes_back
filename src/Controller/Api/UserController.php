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
     * @Route("/api/client/{id<\d+>}/info", name="api_client_info", methods={"GET"})
     */
    public function getItemClient(User $user = null): Response
    {
        // 404 ?
        if ($user === null) {
            return $this->json(['error' => 'Client non trouvé.'], Response::HTTP_NOT_FOUND);
        }

       
        return $this->json(
            $user,
            Response::HTTP_OK,
            [],
            [
                'groups' => [ 'client_id']
            ]
        );
    }
     /**
     * 
     * @Route("/api/login_check", name="api_login_check", methods={"GET"})
     */

     /**
     * sauvgarde les modification du compte utilisateur
     * @Route("/api/client/{id<\d+>}", name="api_client_id_post", methods={"POST"})
     */
    public function postClientId(Request $request, SerializerInterface $serializer, ManagerRegistry $doctrine, User $user)
    {
        //Récuperer le contenu JSON
        $jsonContent=$request->getContent();

        //Mise à jour de l'entité User
        $serializer->deserialize($jsonContent, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);
       

        //Validé l'entité

        //On sauvegarde l'entité
        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // On retourne la réponse adapté (200, 204 ou 404)
        //dd($user);
        return $this->json(
            //Le client modifié peut etre ajouté en retour
            $user,
            //Le status code : 200 OK
            //utilisation des constantes de classes
            Response::HTTP_OK,
            
            //Groups
            [
                'groups' => [ 'client_id']
            ]
        );
    }
    /**
     * 
     * @Route("/api/client/register", name="api_client_register_post", methods={"POST"})
     */
    public function postClient(Request $request, SerializerInterface $serializer, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher)
    {
        //Récuperer le contenu JSON
        $jsonContent=$request->getContent();

        //Désérialiser (convertir) le JSON en entité Doctrine User
        $user = $serializer->deserialize($jsonContent, User::class, 'json');

        //Validé l'entité
        $hashedPassword = $userPasswordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
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
