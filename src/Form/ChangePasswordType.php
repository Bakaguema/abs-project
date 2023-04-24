<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('email', EmailType::class, [
                'disabled' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form__input'
                ]
            ])
            ->add('old_password', PasswordType::class, [
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Votre ancien mot de passe'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identique.',
                'constraints' => new Length([
                    'min' => 8,
                    'max' => 255,
                    'minMessage' => 'Votre mot de passe est trop court',
                    'maxMessage' => 'Votre mot de passe est trop long.'
                ]),
                'required' => true,
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form__input',
                        'placeholder' => 'Votre nouveau mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form__input',
                        'placeholder' => 'Confirmer votre nouveau mot de passe'
                    ]
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
