<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Service\Arborescence;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * Get all product
     * @Route("/api/product", name="api_product")
     */
    public function getProducts(ProductRepository $productRepository, SerializerInterface $serializer, Arborescence $Arborescence): Response
    {
        // @todo : retourner tous les produits de la BDD
        // On va chercher les données
        $productsList = $productRepository->findByOnline();

        // récupération des données de l'entité
        $json = $serializer->serialize(
            $productsList,
            'json',
            ['groups' => 'product']
        );

        // code pour enrichir le json avec l'arborescence de catégorie
        $productsArray = json_decode($json);
        $productsArrayToJson = array();
        foreach($productsArray as $thisProduct) {
            $thisProduct->arborescence = $Arborescence->getArboCat($thisProduct->category->id);
            if (strpos($thisProduct->picture, '//') === false) {
                // il faut retourner un lien complet vers l'image
                $thisProduct->picture = $this->container->get('router')->getContext()->getBaseUrl() . '/upload/' . $thisProduct->picture;
            }
            $productsArrayToJson[] = $thisProduct;
        }
        return $this->json(
            $productsArrayToJson,
            Response::HTTP_OK
        );
    }

    /**
     * Retourne la liste complète des infos d'un produit
     * Get info one product
     * @Route("/api/product/{id<\d+>}/info", name="api_product_info", methods={"GET"})
     */
    public function getItemProduct(Product $product = null, SerializerInterface $serializer, Arborescence $Arborescence): Response
    {
        // 404 ?
        if ($product === null) {
            return $this->json(['error' => 'Produit non trouvé.'], Response::HTTP_NOT_FOUND);
        }
        // récupération des données de l'entité
        $json = $serializer->serialize(
            $product,
            'json',
            ['groups' => 'product_info']
        );

        // code pour enrichir le json avec l'arborescence de catégorie
        $thisProduct = json_decode($json);
        $productsArrayToJson = array();
        $thisProduct->arborescence = $Arborescence->getArboCat($thisProduct->category->id);
        if (strpos($thisProduct->picture, '//') === false) {
            // il faut retourner un lien complet vers l'image
            $thisProduct->picture = $this->container->get('router')->getContext()->getBaseUrl() . '/upload/' . $thisProduct->picture;
        }
        $productsArrayToJson[] = $thisProduct;
        return $this->json(
            $productsArrayToJson,
            Response::HTTP_OK
        );
    }

    /**
     * Get info lite one product
     * @Route("/api/product/{id<\d+>}", name="api_product_lite", methods={"GET"})
     */
    public function getItemProductLite(Product $product = null, SerializerInterface $serializer, Arborescence $Arborescence): Response
    {
        // 404 ?
        if ($product === null) {
            return $this->json(['error' => 'Produit non trouvé.'], Response::HTTP_NOT_FOUND);
        }
        // récupération des données de l'entité
        $json = $serializer->serialize(
            $product,
            'json',
            ['groups' => 'get_product_lite']
        );

        // code pour enrichir le json avec l'arborescence de catégorie
        $thisProduct = json_decode($json);
        $productsArrayToJson = array();
        $thisProduct->arborescence = $Arborescence->getArboCat($thisProduct->category->id);
        if (strpos($thisProduct->picture, '//') === false) {
            // il faut retourner un lien complet vers l'image
            $thisProduct->picture = $this->container->get('router')->getContext()->getBaseUrl() . '/upload/' . $thisProduct->picture;
        }
        $productsArrayToJson[] = $thisProduct;
        return $this->json(
            $productsArrayToJson,
            Response::HTTP_OK
        );
    }

     /**
     * Get selection product
     * @Route("/api/highlighted", name="api_product_highlighted", methods={"GET"})
     */
    public function getProductHighlighted (ProductRepository $productRepository, SerializerInterface $serializer, Arborescence $Arborescence): Response
    {
        $products=$productRepository->findByHighlighted();
        // récupération des données de l'entité
        $json = $serializer->serialize(
            $products,
            'json',
            ['groups' => 'get_product_lite']
        );

        // code pour enrichir le json avec l'arborescence de catégorie
        $productsArray = json_decode($json);
        $productsArrayToJson = array();
        foreach($productsArray as $thisProduct) {
            $thisProduct->arborescence = $Arborescence->getArboCat($thisProduct->category->id);
            if (strpos($thisProduct->picture, '//') === false) {
                // il faut retourner un lien complet vers l'image
                $thisProduct->picture = $this->container->get('router')->getContext()->getBaseUrl() . '/upload/' . $thisProduct->picture;
            }
            $productsArrayToJson[] = $thisProduct;
        }
        return $this->json(
            $productsArrayToJson,
            Response::HTTP_OK
        );
    }

    /**
     * Retourne tous les produits et les catégories d'une categorie donnée
     * @Route("/api/category/{id<\d+>}", name="api_category", methods={"GET"})
     */
    public function getCategory(ProductRepository $productRepository, CategoryRepository $categoryRepository, $id, SerializerInterface $serializer, Arborescence $Arborescence): Response
    {
        $products=$productRepository->findByCategoryId($id);
        $json = $serializer->serialize(
            $products,
            'json',
            ['groups' => 'get_product_lite']
        );

        // code pour enrichir le json avec l'arborescence de catégorie
        $productsArray = json_decode($json);
        $productsArrayToJson = array();
        foreach($productsArray as $thisProduct) {
            $thisProduct->arborescence = $Arborescence->getArboCat($thisProduct->category->id);
            if (strpos($thisProduct->picture, '//') === false) {
                // il faut retourner un lien complet vers l'image
                $thisProduct->picture = $this->container->get('router')->getContext()->getBaseUrl() . '/upload/' . $thisProduct->picture;
            }
            $productsArrayToJson[] = $thisProduct;
        }

        // Récupération des catégories à envoyer dans le JSON également
        $subCategories = $categoryRepository->findSubCategories($id);
        $json = $serializer->serialize(
            $subCategories,
            'json',
            ['groups' => 'get_categories']
        );

        // code pour enrichir le json avec l'arborescence de catégorie
        $categoriesArray = json_decode($json);
        $categoriesArrayToJson = array();
        foreach($categoriesArray as $thisCategory) {
            if (strpos($thisCategory->picture, '//') === false) {
                // il faut retourner un lien complet vers l'image
                $thisCategory->picture = $this->container->get('router')->getContext()->getBaseUrl() . '/upload/' . $thisCategory->picture;
            }
            $thisCategory->arborescence = $Arborescence->getArboCat($thisCategory->id);
            $categoriesArrayToJson[] = $thisCategory;
        }

        return $this->json(
            array('categories'=>$categoriesArrayToJson, 'products'=>$productsArrayToJson),
            Response::HTTP_OK
        );
    }
}
