<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Region;
use App\Entity\Departement;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', SearchType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher...',
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
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addDepartementField($form->getParent(), $form->getData());

            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();
                /* @var $city City */
                // dd($data);
                if($data){  
                $city = $data->getCity();
                $form = $event->getForm();
                // dd($form);
                if ($city) {
                    $departement = $city->getDepartement();
                    $region = $departement->getRegion();
                    $this->addDepartementField($form, $region);
                    $this->addCityField($form, $departement);
                    $this->addRayonField($form, $city);

                    $form->get('region')->setdata($region);
                    $form->get('departement')->setdata($departement);
                } else {
                    $this->addDepartementField($form, null);
                    $this->addCityField($form, null);
                    $this->addRayonField($form, null);


                }
            }
            }
        );

        $builder->setMethod('POST');
    }
    /**
     * Rajoute un champ departement au formulaire
     *
     * @param FormInterface $form
     * @param Region $region
     * @return void
     */
    private function addDepartementField(FormInterface $form, ?Region $region)
    {

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
            function (FormEvent $event) {
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
    private function addCityField(FormInterface $form, ?Departement $departement)
    {

        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder('city', EntityType::class, null, [
            'mapped' => false,
            'class' => City::class,
            'required' => false,
            'placeholder' => $departement ? 'Selectionner la ville' : 'Selectionner un département',
            'auto_initialize' => false,
            'choices' => $departement ? $departement->getCities() : [],
            'disabled' => $departement ? false : true
        ]);
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addRayonField($form->getParent(), $form->getData());
            }
        );
        $form->add($builder->getForm());
        ;
    }
        /**
     * Ajoute un champ rayon au formulaire
     *
     * @param FormInterface $form
     * @param City $city
     * @return void
     */
    private function addRayonField(FormInterface $form, ?City $city)
    {
        $form->add('rayon', ChoiceType::class, [
            'required' => false,
            'placeholder' => 'Choisir dans un rayon de combien de kilomètres?',
            'choices'=>[
                '10 km'=>10,
                '20 km'=>20,
                '30 km'=>30,
                '40 km'=>40,
                '50 km'=>50,
                '60 km'=>60,
                '70 km'=>70,
                '80 km'=>80,
                '90 km'=>90,
                '100 km'=>100,
                '300 km'=>300,
                '500 km'=>500,

            ],
            'disabled' => $city ? false : true

        ]);
    }
 
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}
