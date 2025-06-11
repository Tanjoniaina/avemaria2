<?php

namespace App\DataFixtures;

use App\Pharmaciegros\Entity\Category;
use App\Pharmaciegros\Entity\Product;
use App\Pharmaciegros\Entity\Supplier;
use Faker\Factory;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Supplier
        for ($i=0; $i < 1000; $i++) { 
            $supplier = new Supplier();
            $supplier->setName($faker->company);
            $supplier->setEmail($faker->email);
            $supplier->setPhone($faker->phoneNumber);
            $supplier->setAddress($faker->address);
            $supplier->setIsactive($faker->boolean);

            $manager->persist($supplier);
        }

        //Category
        $categories = [];
        for($i=0; $i < 5; $i++) {
            $category = new Category();
            $category->setName($faker->name);
            $manager->persist($category);
            $categories[] = $category;
        }

        //Product
        for($i=0; $i < 1000; $i++) {
            $product = new Product();
            $product->setName($faker->city);
            $product->setCode($faker->ean13);
            $product->setDescription($faker->text);
            $product->setUnit('mL');
            $product->setDosage($faker->randomNumber);
            $product->setPurchaseprice($faker->randomNumber);
            $product->setSaleprice($faker->randomNumber);
            $product->setStockmin($faker->randomNumber(2));
            $product->setCategory($faker->randomElement($categories));
            $product->setIsactive($faker->boolean);
            $manager->persist($product);
        }
            

        $manager->flush();
    }
}
