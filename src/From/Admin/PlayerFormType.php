<?php

namespace App\Form\Admin;

use App\Entity\League;
use App\Entity\Player;
use App\Entity\Sport;
use App\Entity\Team;
use App\Repository\LeagueRepository;
use App\Repository\SportRepository;
use App\Repository\TeamRepository;
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
            ->add('teams', EntityType::class, array(
                'expanded' => true,
                'class' => Team::class,
                'required' => true,
                'multiple' => true,
                'choice_label' => 'title',
                'label' => 'Equipes',
                'query_builder' => function (TeamRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.title', 'ASC');
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
