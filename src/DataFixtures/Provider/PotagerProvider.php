<?php

namespace App\DataFixtures\Provider;

use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

class PotagerProvider
{

    private $slugger;
    
    public function __construct()
    {
        // On récupère la connexion à la BDD (DBAL ~= PDO)
        // pour exécuter des requêtes manuelles en SQL pur
        $this->slugger = new AsciiSlugger();
    }

    function getProducts() {
        $faker = Factory::create('fr_FR');
        $products = array();
        $i = 1;
        $categoriesList = $this->categoriesList();
        foreach($categoriesList as $category_array) {
            if ($category_array['parent_id']>0) {
                for($j=0;$j<=6;$j++) {
                    if(mt_rand(1, 2) == 1) {
                        $unit_price = $faker->randomFloat(1, 1, 20);
                        $weight_price = 0;
                    } else {
                        $unit_price = 0;
                        $weight_price = $faker->randomFloat(1, 1, 20);
                    }
                    $productName = $faker->word(2);
                    $slug = $this->slugger->slug($productName);
                    $products[] = array(
                        'name' => $productName,
                        'slug' => $slug,
                        'hihlighted' => $faker->randomElement([1, 0]),
                        'online' => $faker->randomElement([1, 0]),
                        'available' => $faker->randomElement([1, 0]),
                        'stock'=> $faker->randomFloat(2,0,20),
                        'summary' =>  $faker->sentence(),
                        'description' =>  $faker->paragraph(),
                        'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450',
                        'categorie_id' => $i,
                        'unit_price' => $unit_price, 
                        'weight_price' => $weight_price,
                        'weight' => $faker->randomFloat(2, 50, 2000),
                        'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')
                    );
                }
            }
            $i++;
        }
        return $products;
    }

    function getCategories() {
        return $this->categoriesList();
    }

    function categoriesList() {
        $faker = Factory::create('fr_FR');
        $category = [
            array('parent_id' => 0, 'name'=>'Légumes', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 0, 'name'=>'Viandes', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 0, 'name'=>'Epicerie', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 0, 'name'=>'Semences', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 1, 'name'=>'Légumes bottes', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 1, 'name'=>'Légumes Feuilles', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 1, 'name'=>'Légumes racines', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 1, 'name'=>'Légumes alliacés', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 1, 'name'=>'Aromatiques', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 1, 'name'=>'Choux', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 2, 'name'=>'Porc', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 2, 'name'=>'Veaux', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 2, 'name'=>'Boeuf', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 2, 'name'=>'Poulet', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 2, 'name'=>'Dinde', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 3, 'name'=>'Sel', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 3, 'name'=>'Soupe', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 3, 'name'=>'Confiture', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 4, 'name'=>'Graines courges', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 4, 'name'=>'Graines tournesols', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 4, 'name'=>'Graines lins', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 4, 'name'=>'Graines chia', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
            array('parent_id' => 4, 'name'=>'Graines fleurs', 'picture' => 'https://picsum.photos/id/'.mt_rand(1, 100).'/300/450', 'updated_at' => $faker->dateTimeBetween('-1 week', '+1 week')),
        ];
        return $category;
    }
}