<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Service\Arborescence;
use App\Repository\CategoryRepository;
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
     * @Route("/api/ariane/{id<\d+>}", name="api_ariane", methods={"GET"})
     */
    public function getBreadCrumbJson($id, Arborescence $Arborescence): Response
    {
        $categoriesArray = $Arborescence->getArboCat($id);
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

}
