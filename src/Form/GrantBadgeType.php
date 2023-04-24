<?php

namespace App\Form;

use App\Entity\Badge;
use App\Entity\User;
use App\Repository\BadgeRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrantBadgeType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'label' => false,
                'class' => User::class,
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.lastname', 'ASC');
                },
                'choice_label' => function (User $user) {
                    return $user->getLastName() . ' ' . $user->getFirstName();
                },
                'required' => true,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Nom de l\'utilisateur'
                ]
            ])
            ->add('badge', EntityType::class, [
                'label' => false,
                'class' => Badge::class,
                'query_builder' => function (BadgeRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->where('b.badgeCategory = :badgeCategory')
                        ->setParameter('badgeCategory', 'honor')
                        ->orderBy('b.name', 'ASC');
                },
                'choice_label' => 'name',
                'required' => true,
                'attr' => [
                    'class' => 'form__input',
                    'placeholder' => 'Nom du badge'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}
