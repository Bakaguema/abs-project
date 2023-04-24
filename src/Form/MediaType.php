<?php

namespace App\Form;

use App\Entity\Pole;
use App\Entity\Media;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => "Titre de l'interview"
                ]
            ])

            ->add('content', CKEditorType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Transcription écrite de l\'interview'
                ]
            ])

            ->add('video', FileType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Vidéo de l\'interview'
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'label' => false,
                'attr' => [
                    'class' => 'form__input'
                ],
                'placeholder' => 'Choisissez une catégorie',
            ])

            ->add('pole', EntityType::class, [
                'class' => Pole::class,
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input'
                ],
                'placeholder' => "Choisissez un pôle d'activité"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
