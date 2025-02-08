<?php

namespace App\Form;

use App\DTO\ContactDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'empty_data' => '',
                'label' => 'First name',
            ])
            ->add('lastName', TextType::class, [
                'empty_data' => '',
                'label' => 'Last name',
            ])
            ->add('email', EmailType::class, [
                'empty_data' => '',
                'label' => 'Email',
                'required' => true,
            ])
            ->add('phone', TelType::class, [
                'empty_data' => '',
                'label' => 'Phone Number',
                'required' => false,
            ])
            ->add('message', TextareaType::class, [
                'empty_data' => '',
                'label' => 'Message',
                'required' => true,
                'attr' => [
                    'cols' => 50,
                    'rows' => 10,
                    'class' => 'materialize-textarea'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class,
        ]);
    }
}
