<?php

namespace App\Service;

use App\Repository\CategoryRepository;

Class Arborescence {

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getArboCat($id, $output_array = array())
    {
        $ParentCategory = $this->categoryRepository->findParentCategory($id);
        $output_array[$ParentCategory['slug']] = $id;
        if ($ParentCategory['parent_id'] !== null) {
            return $this->getArboCat($ParentCategory['parent_id'], $output_array);
        } else {
            return $output_array;
        }
    }
}
