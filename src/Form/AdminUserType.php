<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir un prénom'
                    ])
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form__input'
                ],
                'disabled' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir un nom'
                    ])
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form__input'
                ],
                'disabled' => true
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir une adresse email'
                    ])
                ], 'required' => true,
                'attr' => [
                    'class' => 'form__input'
                ],
                'disabled' => true
            ])
            ->add('roles', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Modérateur' => 'ROLE_MODO',
                    'Éditeur' => 'ROLE_POLE',
                    'Abonné' => 'ROLE_SUB',
                    'Utilisateur' => 'ROLE_USER'
                ],
                'expanded' => true,
                'multiple' => true
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
