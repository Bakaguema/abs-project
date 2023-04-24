<?php

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserCreate extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Saisir un prénom'
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Saisir un nom'
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Saisir un email',
                    'class' => 'form__input'
                ],

            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                    'label' => false,
                    'attr' => [
                        'class' => 'form__input',
                        'placeholder' => 'Mot de passe (pas de saisie)'
                    ],
                    'disabled' => true
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