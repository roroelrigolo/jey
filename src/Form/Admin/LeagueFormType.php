<?php

namespace App\Form\Admin;

use App\Entity\League;
use App\Entity\Sport;
use App\Repository\SportRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeagueFormType extends AbstractType
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
            ->add('sports', EntityType::class, array(
                'expanded' => true,
                'class' => Sport::class,
                'required' => true,
                'multiple' => true,
                'choice_label' => 'title',
                'label' => 'Sports',
                'query_builder' => function (SportRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.title', 'ASC');
                }
            ))
            ->add('available', CheckboxType::class, [
                'label' => "Valide ?",
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => League::class,
        ]);
    }
}
