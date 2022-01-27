<?php

namespace App\DataFixtures\Provider;

use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

class PotagerProvider
{

    private $slugger;
    private $faker;
    private $nbClient;

    public function __construct()
    {
        $this->slugger = new AsciiSlugger();
        $this->faker = Factory::create('fr_FR');
        $this->nbClient = 10;
    }
    
    public function getUsers()
    {
        $users = array();
        // Azerty
        $hashedPassword = '$2y$13$siqh.kbCV5/snSPL8E/hFOlT3RUyVIbZprSr7XIel3wFUol6YpLtW';
        for($i=0;$i<$this->nbClient;$i++) {
            // $this->nbClient utilisateurs
            $users[] = array(
                'password' => $hashedPassword,
                'email' => $this->faker->safeEmail(),
                'roles' => ["ROLE_USER"],
                'firstname' => $this->faker->firstName(),
                'lastname' => $this->faker->lastName(),
                'address' => $this->faker->streetAddress(),
                'zip' => $this->faker->departmentNumber(),
                'city' => $this->faker->city(),
                'country' => 'France',
                'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week'),
                'phone' => $this->faker->phoneNumber(),
            );
        }
        return $users;
    }

    function getProducts() {
        $products = array();
        $i = 1;
        $categoriesList = $this->categoriesList();
        foreach($categoriesList as $category_array) {
            if ($category_array['parent_id']>0) {
                // pour toutes les catégories qui ne sont pas des catégories mères (car les catégories mères n'ont pas de produits associés)
                for($j=0;$j<=6;$j++) {
                    // 6 produits par catégorie

                   
                    // On spécifie le nom, que l'on sluggify dans la foulée.
                    $productName = $this->faker->unique()->words(2, true);
                    $slug = $this->slugger->slug($productName);
                    $products[] = array(
                        'name' => $productName,
                        'slug' => $slug,
                        'hihlighted' => $this->faker->randomElement([1, 0]),
                        'online' => $this->faker->randomElement([1, 0]),
                        'available' => $this->faker->randomElement([1, 0]),
                        'stock'=> $this->faker->randomFloat(2,0,20),
                        'summary' =>  $this->faker->sentence(),
                        'description' =>  $this->faker->paragraph(),
                        'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450',
                        'categorie_id' => $i,
                        'unit_type' => $this->faker->randomElement([1, 0]), 
                        'price' => $this->faker->randomFloat(1, 1, 20),
                        'weight' => $this->faker->randomNumber(4, false),
                        'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')
                    );
                }
            }
            $i++;
        }
        return $products;
    }


    function getCommand() {
        $commands = array();
        // pour chaque client
        for ($i=1;$i<=$this->nbClient;$i++) {
            // entre 0 et 6 commandes pour chaque client
            $nbCommand = mt_rand(0,6);
            for ($j=1;$j<=$nbCommand;$j++) {
                $commands[] = array(
                    'num_fact' => '#Fact_0000'.$i.$j,
                    'status' => $this->faker->randomElement(['Préparé', 'En cours', 'Payé']),
                    'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week'),
                    'total_ttc' => $this->faker->randomFloat(2, 5, 300),
                    'total_ht' => $this->faker->randomFloat(2, 5, 300),
                    'total_tva' => $this->faker->randomFloat(2, 5, 300),
                    'total_ttc' => $this->faker->randomFloat(2, 5, 300),
                    'user_id' => $i,
                );
            }
        }

        return $commands;
    }


    function getCategories() {
        // retourne simplement toutes les catégories pour être crées
        return $this->categoriesList();
    }

    function categoriesList() {
        // listes des catégories. On boucle sur cette liste pour créer les produits
        $category = [
            array('parent_id' => 0, 'slug'=>'legumes', 'name'=>'Légumes', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 0, 'slug'=>'viandes', 'name'=>'Viandes', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 0, 'slug'=>'epicerie', 'name'=>'Epicerie', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 0, 'slug'=>'semences', 'name'=>'Semences', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 1, 'slug'=>'legumes-bottes', 'name'=>'Légumes bottes', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 1, 'slug'=>'legumes-feuilles', 'name'=>'Légumes Feuilles', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 1, 'slug'=>'legumes-racines', 'name'=>'Légumes racines', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 1, 'slug'=>'legumes-alliaces', 'name'=>'Légumes alliacés', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 1, 'slug'=>'aromatiques', 'name'=>'Aromatiques', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 1, 'slug'=>'choux', 'name'=>'Choux', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 2, 'slug'=>'porc', 'name'=>'Porc', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 2, 'slug'=>'veaux', 'name'=>'Veaux', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 2, 'slug'=>'boeuf', 'name'=>'Boeuf', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 2, 'slug'=>'poulet', 'name'=>'Poulet', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 2, 'slug'=>'dinde', 'name'=>'Dinde', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 3, 'slug'=>'sel', 'name'=>'Sel', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 3, 'slug'=>'soupe', 'name'=>'Soupe', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 3, 'slug'=>'confiture', 'name'=>'Confiture', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 4, 'slug'=>'graines-courges', 'name'=>'Graines courges', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 4, 'slug'=>'graines-tournesols', 'name'=>'Graines tournesols', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 4, 'slug'=>'graines-lins', 'name'=>'Graines lins', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 4, 'slug'=>'graines-chia', 'name'=>'Graines chia', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 4, 'slug'=>'graines-fleurs', 'name'=>'Graines fleurs', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
        ];
        return $category;
    }
}