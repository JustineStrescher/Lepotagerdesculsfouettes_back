<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * Get product
     * @Route("/api/product", name="api_product")
     */
    public function getProducts(ProductRepository $productRepository): Response
    
    {
        // @todo : retourner tous les produits de la BDD

        // On va chercher les données
        $productsList = $productRepository->findAll();

        return $this->json([
             // Les données à sérialiser (à convertir en JSON)
             $productsList,
             // Le status code
             200,
             // Les en-têtes de réponse à ajouter (aucune)
             [],
             // Les groupes à utiliser par le Serializer
             ['groups' => 'get_product']
        ]);
    }
}
