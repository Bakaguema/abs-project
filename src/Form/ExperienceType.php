<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Experience;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('article', EntityType::class, [
                'class' => Article::class,
                'label' => false,
                'attr' => [
                    'class' => 'form__input'
                ],
                'placeholder' => 'Choisissez un article',
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
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => "Titre de votre retour d'expérience"
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
            'data_class' => Experience::class,
        ]);
    }
}
