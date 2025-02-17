<?php

namespace App\DataFixtures;

use App\Entity\Gender;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GenderFixtures extends Fixture
{
    public const GENDERS = [
        'Male',
        'Female',
        'Non-Binary',
        'Other'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::GENDERS as $genderName) {
            $gender = new Gender();
            $gender->setName($genderName);
            
            $manager->persist($gender);
            
            // Créer une référence pour une utilisation ultérieure si nécessaire
            $this->addReference('gender_' . strtolower($genderName), $gender);
        }

        $manager->flush();
    }
}