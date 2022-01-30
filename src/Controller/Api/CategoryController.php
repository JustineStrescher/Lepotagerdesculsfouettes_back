<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Service\Arborescence;
use App\Repository\CategoryRepository;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * Retourne la liste des categories de produit
     * @Route("/api/category", name="api_categories", methods={"GET"})
     */
    public function getMainCategories(CategoryRepository $categoryRepository): Response
    {
        // On va chercher les données
        $categoriesList = $categoryRepository->findMainCategories();

        return $this->json(
            // Les données à sérialiser (à convertir en JSON)
            $categoriesList,
            // Le status code
            200,
            // Les en-têtes de réponse à ajouter (aucune)
            [],
            // Les groupes à utiliser par le Serializer
            ['groups' => 'get_categories']
        );
    }

    /**
     * Retourne la liste des categories de produit
     * @Route("/api/subcategory", name="api_subcategories", methods={"GET"})
     */
    public function getSubCategories(CategoryRepository $categoryRepository, SerializerInterface $serializer, Arborescence $Arborescence): Response
    {
        // On va chercher les données
        $categoriesList = $categoryRepository-> findAllSubCategories();

          // Récupération des catégories à envoyer dans le JSON également
          $json = $serializer->serialize(
              $categoriesList,
              'json',
              ['groups' => 'get_categories']
          );
  
          // code pour enrichir le json avec l'arborescence de catégorie
          $categoriesArray = json_decode($json);
          $categoriesArrayToJson = array();
          foreach($categoriesArray as $thisCategory) {
              $thisCategory->arborescence = $Arborescence->getArboCat($thisCategory->id);
              $categoriesArrayToJson[] = $thisCategory;
          }
  
          return $this->json(
              $categoriesArrayToJson,
              Response::HTTP_OK
          );
    }

    /**
     * @Route("/api/ariane/{id<\d+>}", name="api_ariane", methods={"GET"})
     */
    public function getBreadCrumbJson($id, Arborescence $Arborescence): Response
    {
        $categoriesArray = $Arborescence->getArboCat($id);
        $subCategories = '';
        foreach($categoriesArray as $slug) {
            $subCategories .= ' > ' . $slug;
        }
        return $this->json(
            // Les données à sérialiser (à convertir en JSON)
            $subCategories,
            // Le status code
            200,
            // Les en-têtes de réponse à ajouter (aucune)
            [],
           
        );
    }
}
