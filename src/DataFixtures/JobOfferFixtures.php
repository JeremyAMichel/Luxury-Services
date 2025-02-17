<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Client;
use App\Entity\JobOffer;
use App\Entity\JobOfferType;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class JobOfferFixtures extends Fixture implements DependentFixtureInterface
{
    public const JOB_OFFERS = [
        // Technology
        ['title' => 'Senior Frontend Developer', 'category' => 'technology', 'type' => 'full_time', 'client' => 'recruiter', 'location' => 'Paris', 'salary' => 65000],
        ['title' => 'Backend Engineer', 'category' => 'technology', 'type' => 'full_time', 'client' => 'recruiter', 'location' => 'Remote', 'salary' => 70000],
        ['title' => 'DevOps Engineer', 'category' => 'technology', 'type' => 'full_time', 'client' => 'recruiter2', 'location' => 'Lyon', 'salary' => 60000],
        
        // Fashion & Luxury
        ['title' => 'Fashion Designer', 'category' => 'fashion___luxury', 'type' => 'full_time', 'client' => 'recruiter3', 'location' => 'Paris', 'salary' => 55000],
        ['title' => 'Luxury Brand Manager', 'category' => 'fashion___luxury', 'type' => 'full_time', 'client' => 'recruiter3', 'location' => 'Milan', 'salary' => 75000],
        ['title' => 'Visual Merchandiser', 'category' => 'fashion___luxury', 'type' => 'part_time', 'client' => 'recruiter4', 'location' => 'Nice', 'salary' => 35000],
        
        // Marketing & PR
        ['title' => 'Digital Marketing Manager', 'category' => 'marketing___pr', 'type' => 'full_time', 'client' => 'recruiter5', 'location' => 'Paris', 'salary' => 58000],
        ['title' => 'PR Specialist', 'category' => 'marketing___pr', 'type' => 'freelance', 'client' => 'recruiter5', 'location' => 'Remote', 'salary' => 400],
        ['title' => 'Social Media Manager', 'category' => 'marketing___pr', 'type' => 'part_time', 'client' => 'recruiter6', 'location' => 'Bordeaux', 'salary' => 32000],
        
        // Commercial
        ['title' => 'Sales Director', 'category' => 'commercial', 'type' => 'full_time', 'client' => 'recruiter7', 'location' => 'Paris', 'salary' => 90000],
        ['title' => 'Business Development Manager', 'category' => 'commercial', 'type' => 'full_time', 'client' => 'recruiter7', 'location' => 'Lyon', 'salary' => 65000],
        ['title' => 'Key Account Manager', 'category' => 'commercial', 'type' => 'full_time', 'client' => 'recruiter8', 'location' => 'Marseille', 'salary' => 55000],
        
        // Retail Sales
        ['title' => 'Boutique Manager', 'category' => 'retail_sales', 'type' => 'full_time', 'client' => 'recruiter9', 'location' => 'Cannes', 'salary' => 45000],
        ['title' => 'Senior Sales Associate', 'category' => 'retail_sales', 'type' => 'full_time', 'client' => 'recruiter9', 'location' => 'Monaco', 'salary' => 35000],
        ['title' => 'Luxury Retail Advisor', 'category' => 'retail_sales', 'type' => 'seasonal', 'client' => 'recruiter10', 'location' => 'Saint-Tropez', 'salary' => 30000],
        
        // Creative
        ['title' => 'Creative Director', 'category' => 'creative', 'type' => 'full_time', 'client' => 'recruiter', 'location' => 'Paris', 'salary' => 85000],
        ['title' => 'Art Director', 'category' => 'creative', 'type' => 'freelance', 'client' => 'recruiter2', 'location' => 'Remote', 'salary' => 500],
        ['title' => 'UI/UX Designer', 'category' => 'creative', 'type' => 'full_time', 'client' => 'recruiter3', 'location' => 'Lyon', 'salary' => 50000],
        
        // Management & HR
        ['title' => 'HR Director', 'category' => 'management___hr', 'type' => 'full_time', 'client' => 'recruiter4', 'location' => 'Paris', 'salary' => 80000],
        ['title' => 'Talent Acquisition Manager', 'category' => 'management___hr', 'type' => 'full_time', 'client' => 'recruiter5', 'location' => 'Toulouse', 'salary' => 55000],
        ['title' => 'HR Business Partner', 'category' => 'management___hr', 'type' => 'part_time', 'client' => 'recruiter6', 'location' => 'Lille', 'salary' => 45000],
        
        // Additional positions
        ['title' => 'Luxury Hotel Manager', 'category' => 'management___hr', 'type' => 'full_time', 'client' => 'recruiter7', 'location' => 'Nice', 'salary' => 70000],
        ['title' => 'Private Chef', 'category' => 'creative', 'type' => 'full_time', 'client' => 'recruiter8', 'location' => 'Monaco', 'salary' => 65000],
        ['title' => 'Yacht Steward/Stewardess', 'category' => 'retail_sales', 'type' => 'seasonal', 'client' => 'recruiter9', 'location' => 'Antibes', 'salary' => 40000],
        ['title' => 'Private Jet Cabin Crew', 'category' => 'retail_sales', 'type' => 'full_time', 'client' => 'recruiter10', 'location' => 'Nice', 'salary' => 45000],
        ['title' => 'Luxury Car Sales Specialist', 'category' => 'commercial', 'type' => 'full_time', 'client' => 'recruiter', 'location' => 'Paris', 'salary' => 60000],
        ['title' => 'Fine Jewelry Expert', 'category' => 'retail_sales', 'type' => 'full_time', 'client' => 'recruiter2', 'location' => 'Cannes', 'salary' => 55000],
        ['title' => 'Wine & Spirits Consultant', 'category' => 'commercial', 'type' => 'part_time', 'client' => 'recruiter3', 'location' => 'Bordeaux', 'salary' => 35000],
        ['title' => 'Luxury Event Planner', 'category' => 'marketing___pr', 'type' => 'freelance', 'client' => 'recruiter4', 'location' => 'Paris', 'salary' => 450],
        ['title' => 'Personal Shopper', 'category' => 'retail_sales', 'type' => 'full_time', 'client' => 'recruiter5', 'location' => 'Paris', 'salary' => 40000],
        ['title' => 'Brand Ambassador', 'category' => 'marketing___pr', 'type' => 'temporary', 'client' => 'recruiter6', 'location' => 'Multiple', 'salary' => 35000],
        ['title' => 'Interior Designer', 'category' => 'creative', 'type' => 'full_time', 'client' => 'recruiter7', 'location' => 'Paris', 'salary' => 50000]
    ];

    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger();
        
        foreach (self::JOB_OFFERS as $index => $offerData) {
            $offer = new JobOffer();
            
            // Données de base
            $offer->setJobTitle($offerData['title']);
            $offer->setDescription($this->generateDescription($offerData['title']));
            $offer->setIsActive(true);
            $offer->setJobLocation($offerData['location']);
            $offer->setSalary($offerData['salary']);
            
            // Dates
            $offer->setCreatedAt(new DateTimeImmutable('now'));
            $offer->setClosingAt(new DateTimeImmutable('+30 days'));
            
            // Références aux autres entités
            $offer->setCategory($this->getReference('category_' . $offerData['category'], Category::class));
            $offer->setJobType($this->getReference('job_type_' . $offerData['type'], JobOfferType::class));
            $offer->setClient($this->getReference('client_' . $offerData['client'], Client::class));
            
            // Générer la référence unique
            $offer->setReference(sprintf('JOB-%s-%04d', (new DateTimeImmutable())->format('Ymd'), $index + 1));
            
            // Générer le slug
            $slug = $slugger->slug(strtolower($offerData['title']));
            $offer->setSlug($slug . '-' . substr(uniqid(), -6));
            
            $manager->persist($offer);
        }

        $manager->flush();
    }

    private function generateDescription(string $title): string
    {
        return "We are currently seeking a highly qualified $title to join our prestigious team. 
        The ideal candidate will have extensive experience in luxury services and a proven track record of excellence. 
        This position offers an exciting opportunity to work with premium brands and high-net-worth clients in an 
        international environment. The role requires excellent communication skills, attention to detail, and a deep 
        understanding of luxury market standards. Join us in delivering exceptional experiences to our discerning clientele.";
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            JobOfferTypeFixtures::class,
            ClientFixtures::class,
        ];
    }
}