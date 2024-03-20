<?php

namespace App\Form\Admin;

use App\Entity\Message;
use App\Entity\Notification;
use App\Entity\NotificationType;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\MessageRepository;
use App\Repository\NotificationTypeRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, array(
                'class' => User::class,
                'required' => true,
                'choice_label' => 'pseudo',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez un utilisateur',
                'label' => 'Utilisateur',
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.pseudo', 'ASC');
                }
            ))
            ->add('type', EntityType::class, array(
                'class' => NotificationType::class,
                'required' => true,
                'choice_label' => 'title',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez un type de notification',
                'label' => 'Type',
                'query_builder' => function (NotificationTypeRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.title', 'ASC');
                }
            ))
            ->add('message', EntityType::class, array(
                'class' => Message::class,
                'required' => false,
                'choice_label' => 'content',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez un message',
                'label' => 'Type',
                'query_builder' => function (MessageRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.content', 'ASC');
                }
            ))
            ->add('product', EntityType::class, array(
                'class' => Product::class,
                'required' => false,
                'choice_label' => 'title',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez un produit',
                'label' => 'Type',
                'query_builder' => function (ProductRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.title', 'ASC');
                }
            ))
            ->add('view', CheckboxType::class, [
                'label' => "Vue ?",
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Notification::class,
        ]);
    }
}
