<?php

namespace App\Form\Admin;

use App\Entity\Conversation;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConversationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', EntityType::class, array(
                'class' => Product::class,
                'required' => true,
                'choice_label' => 'title',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez une annonce',
                'label' => 'Annonce',
                'query_builder' => function (ProductRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.title', 'ASC');
                }
            ))
            ->add('users', EntityType::class, array(
                'class' => User::class,
                'multiple' => true,
                'required' => false,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez deux utilisateurs',
                'label' => 'Utilisateur',
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
            'data_class' => Conversation::class,
        ]);
    }
}
