<?php

// src/Service/FileUploader.php
namespace App\Service;

use App\Repository\CommandRepository;

Class TotalCommand{
    public function __construct(CommandRepository $commandRepository){
        $this->commandRepository =$commandRepository;

    }
      /**
     * 
     * Retourne 
     * 
     */
    public function updateTotalCommand($id){
        // on donne un id de commande, pour récuperer les produits qu'elle contient
        $command = $this->commandRepository->find($id);
        // Je récupère les produits de ma commandes
        $productCommand= $command->getProductCommands()
        foreach ($productCommand as $key => $value) {
           
        }
        
    }
}