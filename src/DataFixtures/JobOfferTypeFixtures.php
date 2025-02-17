<?php

namespace App\DataFixtures;

use App\Entity\JobOfferType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JobOfferTypeFixtures extends Fixture
{
    public const JOB_TYPES = [
        'Full time',
        'Part time',
        'Temporary',
        'Freelance',
        'Seasonal'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::JOB_TYPES as $typeName) {
            $jobType = new JobOfferType();
            $jobType->setName($typeName);
            
            $manager->persist($jobType);
            
            // Créer une référence pour une utilisation ultérieure si nécessaire
            $this->addReference('job_type_' . strtolower(str_replace(' ', '_', $typeName)), $jobType);
        }

        $manager->flush();
    }
}