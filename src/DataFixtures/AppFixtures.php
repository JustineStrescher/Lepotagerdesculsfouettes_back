<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\PotagerProvider;

class AppFixtures extends Fixture
{

    private $connection;

    public function __construct(Connection $connection)
    {
        // On récupère la connexion à la BDD (DBAL ~= PDO)
        // pour exécuter des requêtes manuelles en SQL pur
        $this->connection = $connection;
    }

     /**
     * Permet de TRUNCATE les tables et de remettre les AI à 1
     */
    private function truncate()
    {
        // On passe en mode SQL ! On cause avec MySQL
        // Désactivation la vérification des contraintes FK
        $this->connection->executeQuery('SET foreign_key_checks = 0');
        // On tronque
        $this->connection->executeQuery('TRUNCATE TABLE category');
        $this->connection->executeQuery('TRUNCATE TABLE product');
        // etc.
    }

    public function load(ObjectManager $manager): void
    {

        // On TRUNCATE manuellement
        $this->truncate();

        $PotagerProvider = new PotagerProvider();

        foreach($PotagerProvider->getCategories() as $this_category_array) {
            $category = new Category;
            if($this_category_array['parent_id'] == 0) {
                $category_parent = null;
            } else {
                $category_parent = $manager->getRepository(Category::class)->find($this_category_array['parent_id']);
            }
            $category->setParent($category_parent);
            $category->setName($this_category_array['name']);
            $category->setPicture($this_category_array['picture']);
            $category->setUpdatedAt($this_category_array['updated_at']);
            $manager->persist($category);
            $manager->flush();
        }

        foreach ($PotagerProvider->getProducts() as $this_product_array) {
            $product = new Product;
            $product->setName($this_product_array['name']);
            $product->setSlug($this_product_array['slug']);
            $product->setWeight($this_product_array['weight']);
            $product->setWeightPrice($this_product_array['weight_price']);
            $product->setUnitPrice($this_product_array['unit_price']);
            $product->setHihlighted($this_product_array['hihlighted']);
            $product->setOnline($this_product_array['online']);
            $product->setStock($this_product_array['stock']);
            $product->setAvailable($this_product_array['available']);
            $product->setSummary($this_product_array['summary']);
            $product->setDescription($this_product_array['description']);
            $product->setUpdatedAt($this_product_array['updated_at']);
            $product->setPicture($this_product_array['picture']);

            $category = $manager->getRepository(Category::class)->find($this_product_array['categorie_id']);

            $product->setCategory($category);
            $manager->persist($product);
            $manager->flush();
        }
    }
}