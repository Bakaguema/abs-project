<?php

namespace App\Form;

use App\Entity\Work;
use App\Entity\Purpose;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PurposeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => false,
            'required'=>true,
            'attr' => [
                'class' => 'form__input',
                'placeholder' => "Poste recherché"
            ]
        ])
        ->add('domaine', TextType::class,[
            'label'=>false,
            'attr'=> [
                'class'=>'form__input',
                'placeholder'=>'Domaine'
            ]
        ])
        ->add('place', TextType::class,[
            'label'=>false,
            'attr'=> [
                'class'=>'form__input',
                'placeholder'=>'Lieu'
            ]
        ])
        ->add('datedispo', TextType::class,[
            'label'=>false,
            'attr'=>[
                'class'=>'form__input',
                'placeholder'=>"Date à partir de laquelle vous êtes disponible"
            ]
        ])
        ->add('contact', TextType::class,[
            'label'=>false,
            'attr'=>[
                'class'=>'form__input',
                'placeholder'=>'Moyen de contact'
            ]
        ])
        ->add('contrat',ChoiceType::class,[
            'required' => false,
            'placeholder'=>'Type de contrat souhaité',
            'choices'=>[
                'Stage'=>'STAGE',
                'Apprentissage'=>'APPRENTISSAGE',
                'Alternance' => 'ALTERNANCE',
                'Intérim'=>'INTERIM',
                'CDD'=>'CDD',
                'CDI'=>'CDI',
            ],
        ])
        ->add('description',  CKEditorType::class, [
            'label' => false,
            'required'=>true,
            'attr' => [
                'class' => 'form__input',
            ]
        ])
        ->add('cv', CKEditorType::class,[
            'label'=>false,
            'required'=>true,
            'attr'=>[
                'class'=>'form__input',
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Purpose::class,
        ]);
    }
}
