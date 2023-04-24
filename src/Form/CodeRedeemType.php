<?php

namespace App\Form;

use App\Entity\CodeRedeem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CodeRedeemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Utilisation', IntegerType::class, [
                'label' => false,
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => "Saisir le nombre d'utilisation pour le code"
                ],
            ])
            ->add('Nom', TextType::class, [
                'label' => false,
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => "Saisir le nom de la communauté "
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CodeRedeem::class,
        ]);
    }
}