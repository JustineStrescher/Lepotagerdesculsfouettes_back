<?php

namespace App\DataFixtures\Provider;

use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

class PotagerProvider
{

    private $slugger;
    private $faker;
    
    public function __construct()
    {
        $this->slugger = new AsciiSlugger();
        $this->faker = Factory::create('fr_FR');
    }
    
    public function getUsers()
    {
        $users = array();
        // Azerty
        $hashedPassword = '$2y$13$siqh.kbCV5/snSPL8E/hFOlT3RUyVIbZprSr7XIel3wFUol6YpLtW';
        for($i=0;$i<10;$i++) {
            // 10 utilisateurs
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
                    if(mt_rand(1, 2) == 1) {
                        $unit_price = $this->faker->randomFloat(1, 1, 20);
                        $weight_price = 0;
                    } else {
                        $unit_price = 0;
                        $weight_price = $this->faker->randomFloat(1, 1, 20);
                    }
                    $productName = $this->faker->word(2, true);
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
                        'unit_price' => $unit_price, 
                        'weight_price' => $weight_price,
                        'weight' => $this->faker->randomNumber(4, false),
                        'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')
                    );
                }
            }
            $i++;
        }
        return $products;
    }

    function getCategories() {
        // retourne simplement toutes les catégories pour être crées
        return $this->categoriesList();
    }

    function categoriesList() {
        // listes des catégories. On boucle sur cette liste pour créer les produits
        $category = [
            array('parent_id' => 0, 'name'=>'Légumes', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 0, 'name'=>'Viandes', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 0, 'name'=>'Epicerie', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 0, 'name'=>'Semences', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 1, 'name'=>'Légumes bottes', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 1, 'name'=>'Légumes Feuilles', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 1, 'name'=>'Légumes racines', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 1, 'name'=>'Légumes alliacés', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 1, 'name'=>'Aromatiques', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 1, 'name'=>'Choux', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 2, 'name'=>'Porc', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 2, 'name'=>'Veaux', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 2, 'name'=>'Boeuf', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 2, 'name'=>'Poulet', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 2, 'name'=>'Dinde', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 3, 'name'=>'Sel', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 3, 'name'=>'Soupe', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 3, 'name'=>'Confiture', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 4, 'name'=>'Graines courges', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 4, 'name'=>'Graines tournesols', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 4, 'name'=>'Graines lins', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 4, 'name'=>'Graines chia', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
            array('parent_id' => 4, 'name'=>'Graines fleurs', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $this->faker->dateTimeBetween('-2 week', '-1 week')),
        ];
        return $category;
    }
}