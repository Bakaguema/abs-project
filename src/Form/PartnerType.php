<?php

namespace App\Form;

use App\Entity\Partner;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PartnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => "Nom du partenaire"
                ]
            ])
            ->add('resume', CKEditorType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Résumé de l\'activité du partenaire'
                ]
            ])
            ->add('parcours', CKEditorType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Parcours du partenaire'
                ]
            ])
            ->add('rencontres', CKEditorType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Les rencontres du partenaire'
                ]
            ])
            ->add('link', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => "Lien vers la page partenaire"
                ]
            ])

            ->add('profil', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => "Lien vers la page profil"
                ]
            ])
            
            ->add('illustration', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form__input-file'
                ]
            ])

            ->add('pole',ChoiceType::class,[
                'required' => false,
                //  'class' => Pole::class,
                'placeholder'=>'Selectionner un pôle',
                // 'choices' => $pole->getName(),
                //  'multiple' => true,
                //  'expanded' => true,
                'choices'=>[
                    'Evolution des métiers'=>'Evolution des métiers',
                    'Communication, recherche et développement'=>'Communication, recherche et développement',
                    'Evolution et savoirs'=>'Evolution et savoirs',
                    'Immobilier, silver et expérience'=>'Immobilier, silver et expérience',
                    'Universal et Inclusive design'=>'Universal et Inclusive design',
                    'Innovation'=>'Innovation',
                ],
                
            ])

            ->add('pole_1',ChoiceType::class,[
                'required' => false,
                //  'class' => Pole::class,
                'placeholder'=>'Selectionner un pôle',
                // 'choices' => $pole->getName(),
                //  'multiple' => true,
                //  'expanded' => true,
                'choices'=>[
                    'Evolution des métiers'=>'Evolution des métiers',
                    'Communication, recherche et développement'=>'Communication, recherche et développement',
                    'Evolution et savoirs'=>'Evolution et savoirs',
                    'Immobilier, silver et expérience'=>'Immobilier, silver et expérience',
                    'Universal et Inclusive design'=>'Universal et Inclusive design',
                    'Innovation'=>'Innovation',
                ],
                
            ])

            ->add('pole_2',ChoiceType::class,[
                'required' => false,
                //  'class' => Pole::class,
                'placeholder'=>'Selectionner un pôle',
                // 'choices' => $pole->getName(),
                //  'multiple' => true,
                //  'expanded' => true,
                'choices'=>[
                    'Evolution des métiers'=>'Evolution des métiers',
                    'Communication, recherche et développement'=>'Communication, recherche et développement',
                    'Evolution et savoirs'=>'Evolution et savoirs',
                    'Immobilier, silver et expérience'=>'Immobilier, silver et expérience',
                    'Universal et Inclusive design'=>'Universal et Inclusive design',
                    'Innovation'=>'Innovation',
                ],
                
            ])

            ->add('pole_3',ChoiceType::class,[
                'required' => false,
                //  'class' => Pole::class,
                'placeholder'=>'Selectionner un pôle',
                // 'choices' => $pole->getName(),
                //  'multiple' => true,
                //  'expanded' => true,
                'choices'=>[
                    'Evolution des métiers'=>'Evolution des métiers',
                    'Communication, recherche et développement'=>'Communication, recherche et développement',
                    'Evolution et savoirs'=>'Evolution et savoirs',
                    'Immobilier, silver et expérience'=>'Immobilier, silver et expérience',
                    'Universal et Inclusive design'=>'Universal et Inclusive design',
                    'Innovation'=>'Innovation',
                ],
                
            ])

            ->add('pole_4',ChoiceType::class,[
                'required' => false,
                //  'class' => Pole::class,
                'placeholder'=>'Selectionner un pôle',
                // 'choices' => $pole->getName(),
                //  'multiple' => true,
                //  'expanded' => true,
                'choices'=>[
                    'Evolution des métiers'=>'Evolution des métiers',
                    'Communication, recherche et développement'=>'Communication, recherche et développement',
                    'Evolution et savoirs'=>'Evolution et savoirs',
                    'Immobilier, silver et expérience'=>'Immobilier, silver et expérience',
                    'Universal et Inclusive design'=>'Universal et Inclusive design',
                    'Innovation'=>'Innovation',
                ],
                
            ])

            ->add('pole_5',ChoiceType::class,[
                'required' => false,
                //  'class' => Pole::class,
                'placeholder'=>'Selectionner un pôle',
                // 'choices' => $pole->getName(),
                //  'multiple' => true,
                //  'expanded' => true,
                'choices'=>[
                    'Evolution des métiers'=>'Evolution des métiers',
                    'Communication, recherche et développement'=>'Communication, recherche et développement',
                    'Evolution et savoirs'=>'Evolution et savoirs',
                    'Immobilier, silver et expérience'=>'Immobilier, silver et expérience',
                    'Universal et Inclusive design'=>'Universal et Inclusive design',
                    'Innovation'=>'Innovation',
                ],
                
            ])

            ->add('facebook', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Lien facebook'
                ]
            ])
            ->add('twitter', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Lien twitter'
                ]
            ])
            ->add('linkedin', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Lien linkedin'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partner::class,
        ]);
    }
}
