<?php

namespace App\Form;

use App\Entity\Pole;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterPartnerType extends AbstractType
{
   
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //$pole = $options['pole'];
        $builder
            ->add('firstname', TextType::class, [
                'label' => false,
                'constraints' => new Length([
                    'min' => 2,
                    'minMessage' => 'Votre nom doit contenir plus de {{ limit }} charactère.',
                   
                ]),
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Nom de l\'entreprise/Association'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Mail (Visible sur votre page)'
                ]
            ])

            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'invalid_message' => "L'adresse email et la confirmation sont différent.",
                'required' => true,
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form__input',
                        'placeholder' => 'votre@email.com'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form__input',
                        'placeholder' => 'Confirmer votre adresse email'
                    ]
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation sont différent.',
                'required' => true,
                'constraints' => new Length([
                    'min' => 8,
                    'max' => 255,
                    'minMessage' => 'Votre mot de passe est trop court',
                    'maxMessage' => 'Votre mot de passe est trop long.'
                ]),
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form__input',
                        'placeholder' => 'Votre mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form__input',
                        'placeholder' => 'Confirmer votre mot de passe'
                    ]
                ]
            ])

            ->add('pole',ChoiceType::class,[
                'required' => false,
                //  'class' => Pole::class,
                'placeholder'=>'Pôle qui vous intéresse',
                // 'choices' => $pole->getName(),
                //  'multiple' => true,
                //  'expanded' => true,
                'choices'=>[
                    'Evolution des métiers'=>0,
                    'Communication, recherche et développement'=>1,
                    'Evolution et savoirs'=>2,
                    'Immobilier, silver et expérience'=>3,
                    'Universal et Inclusive design'=>4,
                    'Innovation'=>5,
                ],
                
            ])
                

            ->add('picture', FileType::class, [
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'class' => 'form__input-file'
                    ]
            ])

            ->add('profil',NULL,[
                'required' => false,
                'empty_data' => 'partenaire',

            ])
           
            ->add('conditions', CheckboxType::class, [
                'label' => false
            ])
            
            ->add('is_subscribe', CheckboxType::class, [
                'required' => false,
                'label'=> 'Souhaitez-vous vous abonner à la Newsletter ?'  
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
           // 'pole' => array(),
        ]);
    }
}