<?php

namespace App\Form\Admin;

use App\Entity\Department;
use App\Entity\User;
use App\Repository\DepartmentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom"
            ])
            ->add('firstName', TextType::class, [
                'label' => "Prénom"
            ])
            ->add('pseudo', TextType::class, [
                'label' => "Pseudo"
            ])
            ->add('email')
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => "Mot de passe",
                'required' => false,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('location', EntityType::class, array(
                'class' => Department::class,
                'required' => true,
                'choice_label' => 'title',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez un departement',
                'label' => 'Département',
                'query_builder' => function (DepartmentRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.title', 'ASC');
                }
            ))
            ->add('description', TextareaType::class, [
                'label' => "Description",
                'attr' => [
                    'rows' => '5'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
