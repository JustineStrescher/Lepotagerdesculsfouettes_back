<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * Get all product
     * @Route("/api/product", name="api_product")
     */
    public function getProducts(ProductRepository $productRepository): Response
    
    {
        // @todo : retourner tous les produits de la BDD

        // On va chercher les données
        $productsList = $productRepository->findByOnline();

        return $this->json([
             // Les données à sérialiser (à convertir en JSON)
             $productsList,
             // Le status code
             200,
             // Les en-têtes de réponse à ajouter (aucune)
             [],
             // Les groupes à utiliser par le Serializer
             ['groups' => 'product']
        ]);
    }

     /**
      * Retourne la liste complète des infos d'un produit
     * Get info one product
     * @Route("/api/product/{id<\d+>}/info", name="api_product_info", methods={"GET"})
     */
    public function getItemProduct(Product $product = null): Response
    {
        // 404 ?
        if ($product === null) {
            return $this->json(['error' => 'Produit non trouvé.'], Response::HTTP_NOT_FOUND);
        }

       
        return $this->json(
            $product,
            Response::HTTP_OK,
            [],
            [
                'groups' => [ 'product_info']
            ]
        );
    }
      /**
     * Get info lite one product
     * @Route("/api/product/{id<\d+>}", name="api_product_lite", methods={"GET"})
     */
    public function getItemProductLite(Product $product = null): Response
    {
        // 404 ?
        if ($product === null) {
            return $this->json(['error' => 'Produit non trouvé.'], Response::HTTP_NOT_FOUND);
        }

       
        return $this->json(
            $product,
            Response::HTTP_OK,
            [],
            [
                'groups' => [ 'get_product_lite']
            ]
        );
    }
     /**
     * Get selection product
     * @Route("/api/highlighted", name="api_product_highlighted", methods={"GET"})
     */
    public function getProductHighlighted (ProductRepository $productRepository): Response
    {
        $products=$productRepository->findByHighlighted();
       
        return $this->json(
            $products,
            Response::HTTP_OK,
            [],
            [
                'groups' => [ 'get_product_lite']
            ]
        );
    }
    /**
     * Appeller tous les produits d'une categorie
     * @Route("/api/category/{id<\d+>}", name="api_category", methods={"GET"})
     */
    public function getCategory(ProductRepository $productRepository, $id): Response
    {
        $products=$productRepository->findByCategoryId($id);
       
        return $this->json(
            $products,
            Response::HTTP_OK,
            [],
            [
                'groups' => [ 'get_product_lite']
            ]
        );
    }
    
    

    
    

}
