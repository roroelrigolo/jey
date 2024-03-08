<?php

namespace App\Form\Admin;

use App\Entity\Alert;
use App\Entity\Product;
use App\Entity\User;
use App\Enum;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlertFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('depositor', EntityType::class, array(
                'class' => User::class,
                'required' => true,
                'choice_label' => 'pseudo',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez un utilisateur',
                'label' => 'Déposeur',
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.pseudo', 'ASC');
                }
            ))
            ->add('statut', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'Selectionnez un statut',
                'label' => 'Statut',
                'choices' => array_combine(Enum::$alert_statuts, Enum::$alert_statuts),
            ])
            ->add('type', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'Selectionnez un type',
                'label' => 'Type',
                'choices' => array_combine(Enum::$alert_types, Enum::$alert_types),
            ])
            ->add('product', EntityType::class, array(
                'class' => Product::class,
                'required' => false,
                'choice_label' => 'title',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez une annonce',
                'label' => 'Annonce',
                'query_builder' => function (ProductRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.title', 'ASC');
                }
            ))
            ->add('user', EntityType::class, array(
                'class' => User::class,
                'required' => false,
                'choice_label' => 'pseudo',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez un utilisateur',
                'label' => 'Utilisateur',
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.pseudo', 'ASC');
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Alert::class,
        ]);
    }
}
