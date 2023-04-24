<?php

namespace App\Form;

use App\Entity\Signalement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SignalementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', ChoiceType::class, [
                'label' => false,
                'required' => true,
                'expanded' => false,
                'choices' => [

                    'Conflit entre les membres ' => 'Conflit entre les membres ',
                    ' Fausse information ' =>' Fausse information ',
                    'Contenu indésirable  ' => 'Contenu indésirable  ',
                    'Discours haineux  ' => 'Discours haineux  ',
                    'Contenu choquant  ' => 'Contenu choquant  ',
                    'Intimidation/Harcèlement  ' => 'Intimidation/Harcèlement  ',
                    ' Escroquerie ' => ' Escroquerie ',
                    ' Spam ' => ' Spam ',
                    ' Vente interdite ' => ' Vente interdite ',
                    ' Autre ' => ' Autre '
                ]
            ])
            ->add('autre', TextareaType::class,[
                'label' => false,
                'required' => false,
                'attr' => [
                'placeholder' => 'Précisez...',
                'class' => 'textAreaSignalement'
                ]
                
                
                
            ])
            // ->add('user')
            // ->add('comment')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Signalement::class,
        ]);
    }
}
