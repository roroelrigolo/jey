<?php

namespace App\Form\Front;

use App\Entity\NotificationType;
use App\Entity\User;
use App\Repository\NotificationTypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AccountNotificationsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('notifications_type', EntityType::class, array(
                'expanded' => true,
                'class' => NotificationType::class,
                'required' => true,
                'multiple' => true,
                'choice_label' => 'type',
                'label' => 'Notifications actives',
                'query_builder' => function (NotificationTypeRepository $er) {
                    return $er->createQueryBuilder('n')
                        ->orderBy('n.category', 'ASC');
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
