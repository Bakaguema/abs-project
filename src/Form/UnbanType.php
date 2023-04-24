<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UnbanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('email')
            // ->add('roles')
            // ->add('password')
            // ->add('firstname')
            // ->add('lastname')
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
            // ->add('deletedAt')
            // ->add('favorites')
            // ->add('favoritesExp')
            ->add('save', SubmitType::class, [
                'attr' => [
                    'id' =>'unban_button'
                ],
                'label' => 'Débannir le compte'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
