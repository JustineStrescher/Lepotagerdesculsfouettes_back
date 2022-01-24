<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * 
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
     * 
     * @Route("/api/client/{id<\d+>}", name="api_client", methods={"GET"})
     */
    public function getClientId(UserRepository $userRepository, $id){
        $client=$userRepository->findByClientId($id);
       
        return $this->json(
            $client,
            Response::HTTP_OK,
            [],
            [
                'groups' => [ 'client_id']
            ]);

    }
     /**
     * 
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
                'groups' => [ 'user_info']
            ]);
    }
     /**
     * 
     * @Route("/api/login_check", name="api_login_check", methods={"GET"})
     */
}
