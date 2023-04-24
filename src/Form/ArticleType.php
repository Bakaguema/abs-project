<?php

namespace App\Form;

use App\Entity\Pole;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('poles', EntityType::class, [
                'multiple' => true,
                'class' => Pole::class,
                'by_reference' => false,
                'attr' => [
                    'class' => 'select-poles'
                ],
                // va chercher adpole dans l'entité article
            ])
            

            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'label' => false,
                'attr' => [
                    'class' => 'form__input'
                ],
                'placeholder' => 'Choisissez une catégorie',
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
            ->add('video', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Lien vers une vidéo (optionnel)'
                ]
            ])
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => "Titre de l'article"
                ]
            ])

            ->add('content', HiddenType::class)//Contenue alimenté par ckeditor une fois la soumission du formulaire (input caché) 

            ->add('rgpd', CheckboxType::class, [
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
