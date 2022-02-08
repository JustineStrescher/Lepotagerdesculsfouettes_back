<?php

// src/Service/FileUploader.php
namespace App\Service;

use App\Repository\CommandRepository;
use Doctrine\ORM\EntityManagerInterface;

Class TotalCommand{
    private $commandRepository;
    private $entityManager;
    public function __construct(CommandRepository $commandRepository, EntityManagerInterface $entityManager){
        $this->commandRepository = $commandRepository;
        $this->entityManager = $entityManager;

    }
      /**
     * 
     * Retourne 
     * 
     */
    public function updateTotalCommand($id){
        // on donne un id de commande, pour récuperer les produits qu'elle contient
        $taxe_rate = 20;
        $command = $this->commandRepository->find($id);
        // Je récupère les produits de ma commandes
        $productCommand= $command->getProductCommands();
        $totalTTCCommand = 0;
        foreach ($productCommand as $productCommandEntity) {
        //Je récupère  mon total ttc de mon entité ProductCommand et j'additionne via mon foreach tous les TotalTTC de ma boucle
        $totalTTCCommand += $productCommandEntity->getTotalTTC();
       
        }
        //Je calcul le montant ht en fonction du ttc
        $totalHTCommand = $totalTTCCommand * (1 - ($taxe_rate/100)); 
        //Je calcul le montant tva en fonction du ttc et du ht
        $totalTVACommand = $totalTTCCommand - $totalHTCommand;
        //Je remplis l'entité commande avec mon nouveau total ttc ht et tva
        $command->setTotalTTC($totalTTCCommand);
        $command->setTotalHT( $totalHTCommand );
        $command->setTotalTVA($totalTVACommand);

        // J'enregistre mon total ttc de ma commande
        $this->entityManager->persist($command);
        //J'execute ma requète 
        $this->entityManager->flush();
    }
}