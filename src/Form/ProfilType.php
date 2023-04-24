<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\City;
use App\Entity\Departement;
use App\Entity\Region;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('bio', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Votre bio'
                ]
            ])
            ->add('profil',ChoiceType::class,[
                'required' => false,
                'placeholder'=>'votre profil',
                'choices'=>[
                    'demandeur d\'emploi*'=>'demandeur d\'emploi',
                    'recherche d\'experience'=>'junior',
                    'recherche d\'apprenant'=>'senior',
                    'visiteur'=>'visiteur',
                ],
            ])
            ->add('metier', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Votre profession'
                ]
            ])
            ->add('situation', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Votre situation'
                ]
            ])
            ->add('ville', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Votre ville'
                ]
            ])
            ->add('caractere', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Votre caractère'
                ]
            ])
            ->add('interet', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Vos centre d\'interêt'
                ]
            ])
            ->add('objectif', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Vos objectif'
                ]
            ])
            ->add('insight', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Votre insight'
                ]
            ])
            ->add('facebook', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Lien facebook'
                ]
            ])
            ->add('instagram', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Lien instagram'
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
            ->add('pinterest', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Lien pinterest'
                ]
            ])
            ->add('snapchat', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Lien snapchat'
                ]
            ])
            ->add('tiktok', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Lien tiktok'
                ]
            ])
            ->add('youtube', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Lien youtube'
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
            ->add('region', EntityType::class, [
                'mapped' => false,
                'class' => Region::class,
                'placeholder' => 'Selectionner la région',
                'required' => false
                ])
        ;

        $builder->get('region')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                $this->addDepartementField($form->getParent(), $form->getData());
            });

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function(FormEvent $event) {
                $data = $event->getData();
                /* @var $city City */ 
                $city = $data->getCity();
                $form = $event->getForm();

                if ($city) {
                    $departement = $city->getDepartement();
                    $region = $departement->getRegion();
                    $this->addDepartementField($form, $region);
                    $this->addCityField($form, $departement);
                    $form->get('region')->setdata($region);
                    $form->get('departement')->setdata($departement);
                } else {
                    $this->addDepartementField($form, null);
                    $this->addCityField($form, null);
                }
            });
    
    }

     /**
     * Rajoute un champ departement au formulaire
     *
     * @param FormInterface $form
     * @param Region $region
     * @return void
     */
    private function addDepartementField(FormInterface $form, ?Region $region){

        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'departement', 
            EntityType::class,
            null,
            [
                'mapped' => false,
                'class' => Departement::class,
                'placeholder' => $region ? 'Selectionner le département' : 'Selectionner une région',
                'required' => false,
                'auto_initialize' => false,
                'choices' => $region ? $region->getDepartements() : [],
                'disabled' => $region ? false : true
            ]
        );

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                $this->addCityField($form->getParent(), $form->getData());
            }
        );
        $form->add($builder->getForm());
    }
    /**
     * Ajoute un champ city au formulaire
     *
     * @param FormInterface $form
     * @param Departement $departement
     * @return void
     */
    private function addCityField(FormInterface $form, ?Departement $departement) {
        $form->add('city', EntityType::class, [
            'class' => City::class,
            'placeholder' => $departement ? 'Selectionner la ville' : 'Selectionner un département',
            'choices' => $departement ? $departement->getCities() : [],
            'disabled' => $departement ? false : true
        ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);

    }
}