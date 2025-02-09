<?php

namespace App\Form;

use App\Entity\Candidate;
use App\Entity\Gender;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
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
            ->add('currentLocation', TextType::class, [
                'required' => false,
                'label' => 'Current location',
                'attr' => [
                    'id' => 'current_location',
                ],
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'label' => 'Address',
                'attr' => [
                    'id' => 'address',
                ],
            ])
            ->add('country', TextType::class, [
                'required' => false,
                'label' => 'Country',
                'attr' => [
                    'id' => 'country',
                ],
            ])
            ->add('nationality', TextType::class, [
                'required' => false,
                'label' => 'Nationality',
                'attr' => [
                    'id' => 'nationality',
                ],
            ])
            // <input class="datepicker" id="birth_date" name="birth_date" type="text" value="">
			// 					<label for="birth_date">Birthdate</label>
            ->add('birthDate', BirthdayType::class, [
                'required' => false,
                'label' => 'Birthdate',
                // 'widget' => 'single_text',
                'attr' => [
                    'class' => 'datepicker',
                    'id' => 'birth_date',
                ],
                'label_attr' => [
                    'class' => 'active',
                ],
                'format' => 'yyyy-MM-dd',
                
            ])
            // <input id="birth_place" name="birth_place" type="text" value="">
			// 					<label for="birth_place">Birthplace</label>
            // ->add('birthPlace', TextType::class, [
            //     'required' => false,
            //     'label' => 'Birth Place',
            //     'attr' => [
            //         'id' => 'birth_place',
            //     ],
            // ])
            // <textarea class="materialize-textarea" id="description" name="description" cols="50" rows="10"></textarea>
            // <label for="description">Short description for your profile, as well as more personnal informations (e.g. your hobbies/interests ). You can also paste any link you want.</label>
            // ->add('description', TextType::class, [
            //     'required' => false,
            //     'label' => 'Description',
            //     'attr' => [
            //         'id' => 'description',
            //     ],
            // ])
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
