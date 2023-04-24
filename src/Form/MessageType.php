<?php

namespace App\Form;

use App\Entity\Attachments;
use App\Entity\Conversation;
use App\Entity\File as EntityFile;
use App\Entity\Message;
use App\Entity\User;
use DateTime;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\All;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextType::class, [
                'attr' => [
                    'autocomplete' => 'off',
                    'class' => 'chat-input',
                    'placeholder' => 'Écrivez ici pour prendre part à la discussion',
                    'autofocus' => 'autofocus',
                    'data-files-target' => 'fileName'
                ],
                'label' => false,
                'required' => false

            ])
            ->add('attachments', FileType::class, [
                // 'label' => 'Votre image',
                'multiple' => true,
                'attr' => [
                    'style' => 'display:none',
                    'data-files-target' => 'fileButton'
                ],


                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new All([
                        new File([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                'application/pdf',
                                'image/jpg',
                                'image/png',
                                'image/jpeg',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                'application/vnd.ms-powerpoint'

                            ],
                            'mimeTypesMessage' => 'Please upload valid files',
                        ]),
                    ])
                    
                ],
            ])

            // ->add('submit', SubmitType::class, [
            //     'attr' => ['formnovalidate' => 'formnovalidate']
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class, Attachments::class,
            'attr' => [
                'class' => 'chat-input-wrapper',
                'id' => 'chat_form',
                'data-controller' => 'files',
                'data-files-target' => 'formMessage'
            ]
        ]);
    }
}
