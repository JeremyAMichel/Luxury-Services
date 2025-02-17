<?php

namespace App\DataFixtures;

use App\Entity\Experience;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ExperienceFixtures extends Fixture
{
    public const EXPERIENCES = [
        '0 - 6 months',
        '6 months - 1 year',
        '1 - 2 years',
        '2+ years',
        '5+ years',
        '10+ years'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::EXPERIENCES as $experienceName) {
            $experience = new Experience();
            $experience->setName($experienceName);
            
            $manager->persist($experience);
            
            // Créer une référence pour une utilisation ultérieure si nécessaire
            $this->addReference('experience_' . strtolower(str_replace([' ', '+', '-'], '_', $experienceName)), $experience);
        }

        $manager->flush();
    }
}
