<?php

namespace App\Form\Admin;

use App\Entity\NotificationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationTypeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Nom",
                'attr' => array(
                    'placeholder' => "Veuillez saisir le titre"
                )
            ])
            ->add('content', TextareaType::class, [
                'label' => "",
                'attr' => array(
                    'placeholder' => "Veuillez saisir le contenu"
                )
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NotificationType::class,
        ]);
    }
}
