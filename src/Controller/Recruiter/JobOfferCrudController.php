<?php

namespace App\Controller\Recruiter;

use App\Entity\JobOffer;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

class JobOfferCrudController extends AbstractCrudController
{

    public function __construct(private EntityRepository $entityRepository, private SluggerInterface $slugger) {}

    public static function getEntityFqcn(): string
    {
        return JobOffer::class;
    }

    // All jobs created must be linked to the recruiter
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        /** @var User */
        $user = $this->getUser();
        $client = $user->getClient();

        $response = $this->entityRepository->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere('entity.client = :client')->setParameter('client', $client);

        return $response;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('jobTitle'),
            BooleanField::new('isActive'),
            TextField::new('jobLocation'),
            TextEditorField::new('description'),
            DateTimeField::new('closingAt'),
            IntegerField::new('salary'),

            AssociationField::new('jobType')->autocomplete(),
            AssociationField::new('category')->autocomplete(),

            TextField::new('reference')->hideOnForm(),
            TextField::new('slug')->hideOnForm(),
            DateTimeField::new('createdAt')->hideOnForm(),

        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof JobOffer) {
            return;
        }

        /** @var User */
        $user = $this->getUser();
        $entityInstance->setClient($user->getClient());

        // Générer une référence unique
        $reference = $this->generateUniqueReference($entityManager);
        $entityInstance->setReference($reference);

        // Générer le slug si le titre est renseigné
        if ($jobTitle = $entityInstance->getJobTitle()) {
            $slug = $this->generateUniqueSlug($entityManager, $jobTitle);
            $entityInstance->setSlug($slug);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof JobOffer) {
            return;
        }

        // Générer le slug si le titre est renseigné
        if ($jobTitle = $entityInstance->getJobTitle()) {
            $slug = $this->generateUniqueSlug($entityManager, $jobTitle);
            $entityInstance->setSlug($slug);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    /**
     * Génère une référence unique pour une offre d'emploi
     * Format: JOB-YYYYMMDD-XXXX où XXXX est un nombre aléatoire
     */
    private function generateUniqueReference(EntityManagerInterface $entityManager): string
    {
        do {
            // Générer une référence au format JOB-YYYYMMDD-XXXX
            $reference = sprintf(
                'JOB-%s-%04d',
                (new \DateTimeImmutable())->format('Ymd'),
                random_int(0, 9999)
            );

            // Vérifier si la référence existe déjà
            $exists = $entityManager->getRepository(JobOffer::class)
                ->findOneBy(['reference' => $reference]);
        } while ($exists);

        return $reference;
    }

    /**
     * Génère un slug unique pour une offre d'emploi
     * @param string $title Titre de l'offre d'emploi
     */
    private function generateUniqueSlug(EntityManagerInterface $entityManager, string $title): string
    {
        $baseSlug = $this->slugger->slug(strtolower($title));
        $slug = $baseSlug;
        // $counter = 1;

        // Vérifier si le slug existe déjà
        while ($entityManager->getRepository(JobOffer::class)->findOneBy(['slug' => $slug->toString()])) {
            // Ajouter un suffixe unique basé sur timestamp court
            $uniqueSuffix = substr(uniqid(), -4);
            $slug = $this->slugger->slug($baseSlug . '-' . $uniqueSuffix);
        }

        return $slug->toString();
    }
}
