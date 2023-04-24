<?php

namespace App\Form;

use App\Entity\Badge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Positive;

class CreateBadgeType extends AbstractType
{
   
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Nom du badge'
                ]
            ])

            ->add('description', TextareaType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Succinte description dudit badge'
                ]
            ])
            
            ->add('badgeCategory', ChoiceType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                'id' => 'badgeCategory',
                'class' => 'form__input',
                    'placeholder' => 'Catégorie concernée par le badge'
                ],
                'choices' => [
                    'Message' => 'message',
                    'Like' => 'like',
                    'Honorifique' => 'honor',
                ],
            ])
            
            ->add('threshold', IntegerType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Valeur du palier à atteindre pour déblocage'
                ],
            ])
            
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'form__input-file'
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Badge::class,
        ]);
    }
}