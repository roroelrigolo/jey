<?php

namespace App\Form\Admin;

use App\Entity\Subscription;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subscriber', EntityType::class, array(
                'class' => User::class,
                'required' => false,
                'choice_label' => 'pseudo',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez un utilisateur',
                'label' => 'Abonné',
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.pseudo', 'ASC');
                }
            ))
            ->add('account', EntityType::class, array(
                'class' => User::class,
                'required' => false,
                'choice_label' => 'pseudo',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez un utilisateur',
                'label' => 'Compte',
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
            'data_class' => Subscription::class,
        ]);
    }
}
