<?php

namespace App\Form;

use App\Entity\Candidate;
use App\Entity\Gender;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => false,
                'label' => 'First Name',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'first_name',
                ],
            ])
            ->add('lastName', TextType::class, [
                'required' => false,
                'label' => 'Last Name',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'last_name',
                ],
            ])
            ->add('gender', EntityType::class, [
                'class' => Gender::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Choose an option...',
                'label' => 'Gender',
                'attr' => [
                    'id' => 'gender',
                ],
                'label_attr' => [
                    'class' => 'active',
                ],
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->setUpdatedAt(...))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
        ]);
    }

    private function setUpdatedAt(FormEvent $event): void
    {
        $candidate = $event->getData();

        $candidate->setUpdatedAt(new \DateTimeImmutable());
    }
}
