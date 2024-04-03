<?php

namespace App\Form\Front;

use App\Entity\Color;
use App\Entity\Product;
use App\Entity\Textil;
use App\Enum;
use App\Repository\ColorRepository;
use App\Repository\TextilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
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
            ->add('description', TextareaType::class, [
                'label' => "Description",
                'attr' => array(
                    'placeholder' => "Veuillez saisir la description"
                )
            ])
            ->add('price', IntegerType::class, [
                'label' => "Prix",
                'attr' => array(
                    'placeholder' => "Veuillez saisir le prix"
                )
            ])
            ->add('flock', CheckboxType::class, [
                'required' => false,
                'label' => "Il y a t'il un flocage ?"
            ])
            ->add('type', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'Selectionnez un genre',
                'label' => 'Genre',
                'choices' => array_combine(Enum::$types, Enum::$types),
            ])
            ->add('size', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'Selectionnez une taille',
                'label' => 'Taille',
                'choices' => array_combine(Enum::$sizes, Enum::$sizes),
            ])
            ->add('conditionnement', ChoiceType::class, [
                'required' => true,
                'placeholder' => 'Selectionnez un état',
                'label' => 'État',
                'choices' => array_combine(Enum::$conditionnements, Enum::$conditionnements),
            ])
            ->add('number', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'Selectionnez un numéro',
                'label' => 'Numéro',
                'choices' => array_combine(Enum::$numbers, Enum::$numbers),
            ])
            ->add('colors', EntityType::class, array(
                'expanded' => true,
                'class' => Color::class,
                'required' => true,
                'multiple' => true,
                'choice_label' => 'title',
                'label' => 'Couleurs',
                'query_builder' => function (ColorRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.title', 'ASC');
                }
            ))
            ->add('textils', EntityType::class, array(
                'expanded' => true,
                'class' => Textil::class,
                'required' => true,
                'multiple' => true,
                'choice_label' => 'title',
                'label' => 'Matières',
                'query_builder' => function (TextilRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.title', 'ASC');
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
