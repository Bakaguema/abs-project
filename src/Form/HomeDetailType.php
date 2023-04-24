<?php

namespace App\Form;

use App\Entity\HomeDetail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HomeDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('generation', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => "Mini description de la generation boomerang"
                ]
            ])
            ->add('metier', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => "Mini description de l'évolution des métiers"
                ]
            ])
            ->add('communication', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => "Mini description de la communication, recherche & développement"
                ]
            ])
            ->add('savoir', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => "Mini description de l'évolution des savoirs"
                ]
            ])
            ->add('immobilier', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => "Mini description de l'immobilier & silver expérience"
                ]
            ])
            ->add('design', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => "Mini description de l'universal & inclusive design"
                ]
            ])
            ->add('innovation', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => "Mini description de l'innovation"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HomeDetail::class,
        ]);
    }
}
