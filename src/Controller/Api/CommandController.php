<?php

namespace App\Controller\Api;

use App\Entity\Command;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            // [
            //     'groups' => [ 'command_info']
            // ]
        );
    }
  
}
