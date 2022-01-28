<?php

namespace App\Service;

use App\Repository\CategoryRepository;

Class Arborescence {

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * 
     * Retourne toute l'arborescence ascendante d'une catégorie donnée
     * 
     */
    public function getArboCat($id, $slugArray = array())
    {
        // on donne un id de catégorie en paramètre, on récupère son parent
        $ParentCategory = $this->categoryRepository->findParentCategory($id);
        // on stock le slug de la catégorie dans le tableau qui sera retourné
        $slugArray[] = $ParentCategory['slug'];
        if ($ParentCategory['parent_id'] !== null) {
            // On appel la fonction de façon récursive jusqu'a ce que l'on arrive à la categorie mère
            return $this->getArboCat($ParentCategory['parent_id'], $slugArray);
        } else {
            // Le tableau est complété, on va le mettre dans le bon ordre, il faut que le tableau commence par la catégorie mère
            $slugArray = array_reverse($slugArray);
            $i=0;
            foreach($slugArray as $thisSlug) {
                // on va remplir le tableau de sortie avec des index spécifiques
                // Dans la clé, on ajoute le mot Sub pour chaque niveau de catégorie, pour avoir une clé unique en dur exploitable par le front-office
                $output_array[str_repeat('Sub', $i) . 'Category'] = $thisSlug;
                $i++;
            }
            return $output_array;
        }
    }
}
