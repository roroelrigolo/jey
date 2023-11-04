<?php

namespace App\Form;

use App\Entity\League;
use App\Entity\Player;
use App\Entity\Sport;
use App\Entity\Team;
use App\Repository\LeagueRepository;
use App\Repository\SportRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayerFormType extends AbstractType
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
            ->add('sport', EntityType::class, array(
                'class' => Sport::class,
                'required' => true,
                'choice_label' => 'title',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez un sport',
                'label' => 'Sport',
                'query_builder' => function (SportRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.title', 'ASC');
                }
            ))
            ->add('league', EntityType::class, array(
                'class' => League::class,
                'required' => true,
                'choice_label' => 'title',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez une ligue',
                'label' => 'Ligue',
                'query_builder' => function (LeagueRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.title', 'ASC');
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}
