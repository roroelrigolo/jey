<?php

namespace App\Form\Admin;

use App\Entity\Assessment;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssessmentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => "Commentaire"
            ])
            ->add('value', NumberType::class, [
                'label' => "Nombre d'étoiles"
            ])
            ->add('depositor', EntityType::class, array(
                'class' => User::class,
                'required' => true,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez un utilisateur',
                'label' => 'Déposeur',
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }
            ))
            ->add('recipient', EntityType::class, array(
                'class' => User::class,
                'required' => true,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez un utilisateur',
                'label' => 'Receveur',
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assessment::class,
        ]);
    }
}
