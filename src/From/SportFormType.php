<?php

namespace App\Form;

use App\Entity\Sport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Nom",
                'attr' => array(
                    'placeholder' => "Veuillez saisir le nom"
                )
            ])
            ->add('displayMenu', CheckboxType::class, [
                'label' => "Afficher dans le menu ?"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sport::class,
        ]);
    }
}
