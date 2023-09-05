<?php

namespace App\Form;

use App\Entity\League;
use App\Entity\Sport;
use App\Repository\SportRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                'label' => "Nom"
            ])
            ->add('sport', EntityType::class, array(
                'class' => Sport::class,
                'required' => false,
                'choice_label' => 'title',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez un sport',
                'label' => 'Sport',
                'query_builder' => function (SportRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.title', 'ASC');
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => League::class,
        ]);
    }
}
