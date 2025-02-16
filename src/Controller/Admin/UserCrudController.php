<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{


    public function __construct(private UserPasswordHasherInterface $passwordHasher, private EntityRepository $entityRepository) {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Recruiter')
            ->setEntityLabelInPlural('Recruiters')
            ->setPageTitle(Crud::PAGE_INDEX, 'Recruiters')
            ->setPageTitle(Crud::PAGE_NEW, 'Create Recruiter')
            ->setPageTitle(Crud::PAGE_EDIT, 'Edit Recruiter')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Recruiter Details');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email'),
            TextField::new('password', 'Password')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOption('type', PasswordType::class)
                ->setFormTypeOption('first_options', ['label' => 'Password'])
                ->setFormTypeOption('second_options', ['label' => '(Repeat)'])
                ->setRequired($pageName === Crud::PAGE_NEW)
                ->onlyOnForms(),

            // Le role doit être par défaut ROLE_RECRUITER
            // ChoiceField::new('roles')
            //     ->setChoices([
            //         'Admin' => 'ROLE_ADMIN',
            //         'Recruiter' => 'ROLE_RECRUITER',
            //     ])
            //     ->allowMultipleChoices()
            //     ->renderAsBadges(),

            // Par défaut, un recruter créé par un admin doit être vérifié
            // BooleanField::new('isVerified', 'Verified'),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {   
        if ($entityInstance instanceof User) {
            $entityInstance->setRoles(['ROLE_RECRUITER']);
            $entityInstance->setIsVerified(true);
            $this->hashPassword($entityInstance);

            $client = new Client();
            $client->setUser($entityInstance);
        }
        parent::persistEntity($entityManager, $client);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        
        $this->hashPassword($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = $this->entityRepository->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere('entity.roles LIKE :role')
                 ->setParameter('role', '%ROLE_RECRUITER%');

        return $response;
    }

    private function hashPassword(User $user): void
    {
        if ($user->getPassword()) {
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                )
            );
        }
    }
}
