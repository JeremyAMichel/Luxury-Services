<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORIES = [
        'Commercial',
        'Retail sales',
        'Creative',
        'Technology',
        'Marketing & PR',
        'Fashion & luxury',
        'Management & HR'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            
            $manager->persist($category);
            
            // Créer une référence pour une utilisation ultérieure si nécessaire
            $this->addReference('category_' . strtolower(str_replace([' ', '&'], '_', $categoryName)), $category);
        }

        $manager->flush();
    }
}