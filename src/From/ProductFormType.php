<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Image;
use App\Entity\League;
use App\Entity\Player;
use App\Entity\Product;
use App\Entity\Sport;
use App\Entity\Team;
use App\Entity\User;
use App\Repository\BrandRepository;
use App\Repository\ImageRepository;
use App\Repository\LeagueRepository;
use App\Repository\PlayerRepository;
use App\Repository\SportRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Nom"
            ])
            ->add('description')
            ->add('price', NumberType::class, [
                'label' => "Prix"
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
            ->add('team', EntityType::class, array(
                'class' => Team::class,
                'required' => true,
                'choice_label' => 'title',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez une équipe',
                'label' => 'Équipe',
                'query_builder' => function (TeamRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.title', 'ASC');
                }
            ))
            ->add('player', EntityType::class, array(
                'class' => Player::class,
                'required' => true,
                'choice_label' => 'lastName',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez un joueur',
                'label' => 'Joueur',
                'query_builder' => function (PlayerRepository $er) {
                    return $er->createQueryBuilder('j')
                        ->orderBy('j.lastName', 'ASC');
                }
            ))
            ->add('brand', EntityType::class, array(
                'class' => Brand::class,
                'required' => true,
                'choice_label' => 'title',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez une marque',
                'label' => 'Marque',
                'query_builder' => function (BrandRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->orderBy('b.title', 'ASC');
                }
            ))
            ->add('type', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'Selectionnez un genre',
                'label' => 'Genre',
                'choices' => array_combine(\App\Enum::$types, \App\Enum::$types),
            ])
            ->add('size', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'Selectionnez une taille',
                'label' => 'Taille',
                'choices' => array_combine(\App\Enum::$sizes, \App\Enum::$sizes),
            ])
            ->add('conditionnement', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'Selectionnez un état',
                'label' => 'État',
                'choices' => array_combine(\App\Enum::$conditionnements, \App\Enum::$conditionnements),
            ])
            ->add('statement', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'Selectionnez une disponibilité',
                'label' => 'Disponibilité',
                'choices' => array_combine(\App\Enum::$statements, \App\Enum::$statements),
            ])
            ->add('user', EntityType::class, array(
                'class' => User::class,
                'required' => true,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'placeholder' => 'Selectionnez un utilisateur',
                'label' => 'Utilisateur',
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
