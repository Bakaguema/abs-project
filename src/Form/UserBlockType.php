<?php

namespace App\Form;

use App\Entity\UserBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('active')
            // ->add('user1')
            // ->add('user2')
            ->add('submit', SubmitType::class, [
                'label' => 'Oui',
                'validation_groups' => false,
                'label_html' => true,
                
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserBlock::class,
        ]);
    }
}
