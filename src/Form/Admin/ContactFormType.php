<?php

namespace App\Form\Admin;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => "Nom"
            ])
            ->add('firstName', TextType::class, [
                'label' => "Prénom"
            ])
            ->add('email', TextType::class, [
                'label' => "Email"
            ])
            ->add('phone', TextType::class, [
                'label' => "Téléphone"
            ])
            ->add('subject', TextType::class, [
                'label' => "Sujet de la demande"
            ])
            ->add('content', TextareaType::class, [
                'label' => "Message",
                'attr' => [
                    'rows' => '5'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
