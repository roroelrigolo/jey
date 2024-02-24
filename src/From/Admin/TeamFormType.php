<?php

namespace App\Form\Admin;

use App\Entity\League;
use App\Entity\Sport;
use App\Entity\Team;
use App\Repository\LeagueRepository;
use App\Repository\SportRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Nom"
            ])
            ->add('city', TextType::class, [
                'label' => "Ville"
            ])
            ->add('leagues', EntityType::class, array(
                'expanded' => true,
                'class' => League::class,
                'required' => true,
                'multiple' => true,
                'choice_label' => 'title',
                'label' => 'Ligues',
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
            'data_class' => Team::class,
        ]);
    }
}
