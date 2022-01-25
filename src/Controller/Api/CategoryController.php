<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * retourne la liste des catégories filles d'une categorie données
     * @Route("/api/category/subcategory/{id<\d+>}", name="api_sub_categories", methods={"GET"})
     */
    public function getSubCategories($id, CategoryRepository $categoryRepository): Response
    {
        $subCategories = $categoryRepository->findSubCategories($id);
 
        return $this->json(
            // Les données à sérialiser (à convertir en JSON)
            $subCategories,
            // Le status code
            200,
            // Les en-têtes de réponse à ajouter (aucune)
            [],
            // Les groupes à utiliser par le Serializer
            ['groups' => 'get_categories']
        );
    }

    /**
     * @Route("/api/ariane/{id<\d+>}", name="api_ariane", methods={"GET"})
     */
    public function getBreadCrumbJson($id, CategoryRepository $categoryRepository): Response
    {
        $categoriesArray = $this->getBreadCrumb($id, $categoryRepository);
        $subCategories = '';
        foreach($categoriesArray as $this_category) {
            $subCategories .= ' > ' . $this_category;
        }
        return $this->json(
            // Les données à sérialiser (à convertir en JSON)
            $subCategories,
            // Le status code
            200,
            // Les en-têtes de réponse à ajouter (aucune)
            [],
            // Les groupes à utiliser par le Serializer
            // ['groups' => 'get_ariane_category']
        );
    }

    /**
     * 
     * recursive function for breadcrumb
     * 
     */
    public function getBreadCrumb($id, $categoryRepository): array
    {
        $output_array = array();
        $ParentCategory = $categoryRepository->findParentCategory($id);
        if ($ParentCategory->parent_id != 0) {
            $output_array[] = $this->getBreadCrumb($ParentCategory->parent_id, $categoryRepository);
        }
        return $output_array;
    }
}
