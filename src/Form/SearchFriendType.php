<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchFriendType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('query', SearchType::class, [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Rechercher...',
                    ],
                ])
                ->add('submit', SubmitType::class, [
                    'label' => '<i class="bx bx-search-alt-2"></i>',
                    'label_html' => true,
                    'attr' => [
                        'class' => 'button',
                    ]
                ]);
    
            $builder->setMethod('POST');
        }
    
        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'csrf_protection' => false,
            ]);
        }
    }