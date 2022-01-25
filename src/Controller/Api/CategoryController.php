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
        $categoriesArray = $this->getArboList($id, $categoryRepository);
        $reversed = array_reverse($categoriesArray);
        $subCategories = '';
        foreach($reversed as $slug=>$this_category) {
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

    /**
     * 
     * recursive function for breadcrumb
     * 
     */
    public function getArboList($id, $categoryRepository, $output_array = array())
    {
        $ParentCategory = $categoryRepository->findParentCategory($id);
        $output_array[$ParentCategory['slug']] = $ParentCategory['parent_id'];
        if ($ParentCategory['parent_id'] !== null) {
            return $this->getArboList($ParentCategory['parent_id'], $categoryRepository, $output_array);
        } else {
            return $output_array;
        }
    }
}
