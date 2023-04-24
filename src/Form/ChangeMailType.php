<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangeMailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form__input'
                ]
            ])
            ->add('new_email', RepeatedType::class, [
                'type' => EmailType::class,
                'mapped' => false,
                'invalid_message' => 'L\'adresse mail et la confirmation doivent être identique.',
                'required' => true,
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form__input',
                        'placeholder' => 'Votre nouvelle adresse mail'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form__input',
                        'placeholder' => 'Confirmer votre nouvelle adresse mail'
                    ]
                ]
            ])
        
            // ->add('roles')
            ->add('password', PasswordType::class, [
                // 'type' => PasswordType::class,
                'mapped' => false,
                'label' => false,
                'required' => true,
                'invalid_message' => 'Mauvais mot de passe',
                'attr' => [
                    'placeholder' => 'Entrez votre mot de passe'
                ]
                         
            ])
            ->add('firstname', TextType::class, [
                'disabled' => true,
                'label' => false,
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 33
                ]),
                'attr' => [
                    'class' => 'form__input'
                ]
            ])
            ->add('lastname', TextType::class, [
                'disabled' => true,
                'label' => false,
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 33
                ]),
                'attr' => [
                    'class' => 'form__input'
                ]
            ])
            // ->add('picture')
            // ->add('document')
            // ->add('token')
            // ->add('active')
            // ->add('conditions')
            // ->add('bio')
            // ->add('profil')
            // ->add('instagram')
            // ->add('facebook')
            // ->add('twitter')
            // ->add('linkedin')
            // ->add('pinterest')
            // ->add('snapchat')
            // ->add('tiktok')
            // ->add('youtube')
            // ->add('age')
            // ->add('metier')
            // ->add('situation')
            // ->add('ville')
            // ->add('caractere')
            // ->add('interet')
            // ->add('objectif')
            // ->add('insight')
            // ->add('is_subscribe')
            // ->add('pole')
            // ->add('pole_1')
            // ->add('pole_2')
            // ->add('pole_3')
            // ->add('pole_4')
            // ->add('pole_5')
            // ->add('favorites')
            // ->add('favoritesExp')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
