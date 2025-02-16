<?php

namespace App\Controller\Recruiter;

use App\Entity\Client;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class ClientCrudController extends AbstractCrudController
{
    private Security $security;
    private EntityRepository $entityRepository;

    public function __construct(Security $security, EntityRepository $entityRepository)
    {
        $this->security = $security;
        $this->entityRepository = $entityRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Client::class;
    }

  
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('companyName'),
            TextField::new('typeOfActivity'),
            TextField::new('contactName'),
            TextField::new('contactPosition'),
            TextField::new('contactNumber'),
            TextField::new('contactEmail'),

        ];
    }
 

    public function createEntity(string $entityFqcn)
    {
        $client = new Client();
        $client->setUser($this->security->getUser());
        return $client;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission('ROLE_RECRUITER');
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = $this->entityRepository->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere('entity.user = :user')->setParameter('user', $this->getUser());

        return $response;
    }

    public function configureActions(Actions $actions): Actions
    {
        /** @var User */
        $user = $this->security->getUser();
        $existingClient = $user->getClient();

        if ($existingClient) {
            return $actions
                ->disable(Action::NEW);
        }

        return $actions;
    }
}
