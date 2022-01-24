<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\PotagerProvider;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

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
        $this->connection->executeQuery('TRUNCATE TABLE user');
        // etc.
    }

    public function load(ObjectManager $manager): void
    {
        // On TRUNCATE manuellement
        $this->truncate();

        $PotagerProvider = new PotagerProvider();

        foreach($PotagerProvider->getCategories() as $this_category_array) {
            $category = new Category();
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
            $product = new Product();
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

        foreach ($PotagerProvider->getUsers() as $this_user_array) {
            $user = new User();
            $user->setEmail($this_user_array['email']);
            $user->setRoles($this_user_array['roles']);
            $user->setPassword($this_user_array['password']);
            $user->setFirstname($this_user_array['firstname']);
            $user->setLastname($this_user_array['lastname']);
            $user->setAddress($this_user_array['address']);
            $user->setZip($this_user_array['zip']);
            $user->setCity($this_user_array['city']);
            $user->setCountry($this_user_array['country']);
            $user->setUpdatedAt($this_user_array['updated_at']);
            $user->setPhone($this_user_array['phone']);

            $manager->persist($user);
            $manager->flush();
        }
    }
}