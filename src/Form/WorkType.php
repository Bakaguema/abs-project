<?php

namespace App\Form;

use App\Entity\Work;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class WorkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'required'=>true,
                'attr' => [
                    'class' => 'form__input-work',
                    'placeholder' => "Intitulé du poste"
                ]
            ])
            ->add('place', TextType::class,[
                'label'=>false,
                'attr'=> [
                    'class'=>'form__input',
                    'placeholder'=>'Lieu'
                ]
            ])
            ->add('description',  CKEditorType::class, [
                'label' => false,
                'required'=>true,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Ce dont vous avez besoin'
                ]
            ])
            ->add('contrat',ChoiceType::class,[
                'required' => false,
                'placeholder'=>'Type de contrat',
                'choices'=>[
                    'Stage'=>'STAGE',
                    'Apprentissage'=>'APPRENTISSAGE',
                    'Alternance' => 'ALTERNANCE',
                    'Intérim'=>'INTERIM',
                    'CDD'=>'CDD',
                    'CDI'=>'CDI',
                ],
            ])
            ->add('entreprise', TextType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'form__input',
                    'placeholder'=>"Nom de l'entreprise"
                ]
            ])
            ->add('datedebut', TextType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'form__input',
                    'placeholder'=>"Date de début du contrat"
                ]
            ])
            ->add('diplome',ChoiceType::class,[
                'required' => false,
                'placeholder'=>'Diplôme Requis',
                'choices'=>[
                    'Pas de diplôme'=>'aucun',
                    'CAP, BEP'=>'CAP/BEP',
                    'Baccalauréat'=>'BAC',
                    'Brevet de technicien supérieur'=>'BTS',
                    'Licence, licence professionnelle, BUT'=>'LICENSE',
                    'Master'=>'MASTER',
                    'Doctorat'=>'DOCTORAT'
                ],
            ])
            ->add('contact', TextType::class,[
                'label'=>false,
                'attr'=>[
                    'class'=>'form__input',
                    'placeholder'=>'Moyen de contact'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Work::class,
        ]);
    }
}
