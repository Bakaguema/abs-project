<?php

namespace App\Form;

use App\Entity\CodeRedeem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CodeRedeemType2 extends AbstractType
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
                    'placeholder' => "Saisir le nombre d'utilisation à ajouter pour le code"
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