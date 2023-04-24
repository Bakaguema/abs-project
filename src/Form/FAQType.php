<?php

namespace App\Form;

use App\Entity\FAQ;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FAQType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Votre question ?'
                ]
            ])
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Titre de votre réponse'
                ]
            ])
            ->add('response', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Votre réponse'
                ]
            ])
            ->add('linkName', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Titre du bouton (optionnel)'
                ]
            ])
            ->add('linkUrl', UrlType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Lien du bouton (optionnel)'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FAQ::class,
        ]);
    }
}
