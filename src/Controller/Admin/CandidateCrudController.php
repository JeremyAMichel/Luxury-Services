<?php

namespace App\Controller\Admin;

use App\Entity\Candidate;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CandidateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Candidate::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $fields = [
            TextField::new('firstName', 'First Name'),
            TextField::new('lastName', 'Last Name'),
            TextField::new('user.email', 'Email'),
            TextField::new('currentLocation', 'City'),
            TextField::new('jobCategory.name', 'Job Category'),
            DateTimeField::new('createdAt', 'Registration Date')->setFormTypeOption('disabled', true),
            DateTimeField::new('updatedAt', 'Last Modification')->setFormTypeOption('disabled', true),
            DateTimeField::new('deletedAt', 'Deletion Date')->setFormTypeOption('disabled', true),
            TextEditorField::new('description', 'Description'),
            TextEditorField::new('notes', 'Notes'),
        ];

        if ($pageName === 'index' || $pageName === 'detail') {
            $fields[] = UrlField::new('profilPicture', 'Profile Picture')->setTemplatePath('admin/fields/profilPicture_link.html.twig');
            $fields[] = UrlField::new('passport', 'Passport')->setTemplatePath('admin/fields/cv_link.html.twig');
            $fields[] = UrlField::new('cv', 'CV')->setTemplatePath('admin/fields/passport_link.html.twig');
        } else {
            $fields[] = ImageField::new('profilPicture', 'Profile Picture')
            ->setUploadDir('assets/uploads/profile-pictures')
            ->setBasePath('uploads/profile-pictures');

            // TODO upload files fields
            // $fields[] = ImageField::new('passport', 'Passport')
            // ->setUploadDir('assets/uploads/passport')
            // ->setBasePath('uploads/passport');
            // $fields[] = ImageField::new('cv', 'CV')
            // ->setUploadDir('assets/uploads/curriculum-vitae')
            // ->setBasePath('uploads/curriculum-vitae');
        }

        return $fields;
    }
    
}
