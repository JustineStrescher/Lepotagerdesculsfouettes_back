<?php

namespace App\Controller\Api;

use App\Entity\Command;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandController extends AbstractController
{
    /**
     * Returns the detail of an command
     * @Route("/api/command/{id<\d+>}", name="api_command_id", methods={"GET"})
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
}
