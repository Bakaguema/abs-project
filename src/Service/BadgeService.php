<?php

namespace App\Service;

use App\Entity\Badge;
use App\Repository\BadgeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

class BadgeService
{
    public function __construct(
        private EntityManagerInterface $em,
        private Environment $environment,
        private ParameterBagInterface $parameters,
        private BadgeRepository $br
    ) {
    }

    public function handleBadgeFormData(FormInterface $badgeForm, $successFunction): JsonResponse
    {
        if ($badgeForm->isValid()) {
            return call_user_func([$this, $successFunction], $badgeForm);
        } else {
            return $this->handleInvalidForm($badgeForm);
        }
    }

    private function handleValidForm(FormInterface $badgeForm): JsonResponse
    {
        /** @var Badge $badge */
        $badge = $badgeForm->getData();

        /** @var UploadedFile $uploadedImage */
        $uploadedImage = $badgeForm['image']->getData();

        $newFileName = $this->renameUploadedFile($uploadedImage, $this->parameters->get('badges_directory'));
        $badge->setImage($newFileName);

        if ($badge->getBadgeCategory() === "honor") {
            $badge->setThreshold(null);
        }

        $this->em->persist($badge);
        $this->em->flush();

        return new JsonResponse([
            'code' => Badge::BADGE_ADDED_SUCCESSFULLY,
            'html' => $this->environment->render('admin/badge/badges.html.twig', [
                'badges' => $this->br->findAll(),
            ])
        ]);
    }

    public function handleValidEditForm(FormInterface $badgeForm): JsonResponse
    {
        /** @var Badge $badge */
        $badge = $badgeForm->getData();

        $updatedBadge = $this->br->find($badge['hiddenId']);
        // $updatedBadge = $this->br->find($badgeForm->get('hiddenId')->getData());

        /** @var UploadedFile $uploadedImage */
        $uploadedImage = $badgeForm['image']->getData();

        $checkName = $badge['name'] !== null && $badge['name'] !== $updatedBadge->getName();
        $checkDescription = $badge['description'] !== null && $badge['description'] !== $updatedBadge->getDescription();
        $checkCategory = $badge['badgeCategory'] !== null && $badge['badgeCategory'] !== $updatedBadge->getBadgeCategory();
        $checkTreshold = $badge['threshold'] !== null && $badge['threshold'] !== $updatedBadge->getThreshold();
        $checkImg = $uploadedImage !== null;
        $checkCategoryType = $badge['badgeCategory'] === "honor";

        if ($checkCategoryType) {
            $badge['threshold'] = null;
            $checkTreshold = true;
        }


        if ($checkName || $checkDescription || $checkCategory || $checkTreshold || $checkImg) {
            if ($checkImg) {
                $newFileName = $this->renameUploadedFile($uploadedImage, $this->parameters->get('badges_directory'));
                $updatedBadge->setImage($newFileName);
            }

            if ($checkName) {
                $updatedBadge->setName($badge['name']);
            }

            if ($checkDescription) {
                $updatedBadge->setDescription($badge['description']);
            }

            if ($checkCategory) {
                $updatedBadge->setBadgeCategory($badge['badgeCategory']);
            }

            if ($checkTreshold || $checkCategoryType) {
                $updatedBadge->setThreshold($badge['threshold']);
            }

            $this->em->persist($updatedBadge);
            $this->em->flush($updatedBadge);
        }

        return new JsonResponse([
            'code' => Badge::BADGE_UPDATED_SUCCESSFULLY,
            'html' => $this->environment->render('admin/badge/badges.html.twig', [
                'badges' => $this->br->findAll(),
            ]),
        ]);
    }

    private function handleInvalidForm(FormInterface $badgeForm): JsonResponse
    {
        return new JsonResponse([
            'code' => badge::BADGE_INVALID_FORM,
            'errors' => $this->getErrorMessages($badgeForm)
        ]);
    }

    private function renameUploadedFile(UploadedFile $uploadedFile, string $directory): string
    {
        $newFileName = uniqid(more_entropy: true) . ".{$uploadedFile->guessExtension()}";
        $uploadedFile->move($directory, $newFileName);

        return $newFileName;
    }


    private function getErrorMessages(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
    //     private $entityManager;
    //     private $badgeRepository;
    //     private $forumMessageRepository;
    //     private $userRepository;
    //     private $managerRegistry;

    //     public function __construct(BadgeRepository $badgeRepository, ForumMessageRepository $forumMessageRepository, UserRepository $userRepository, EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry)
    //     {        
    //         $this->badgeRepository = $badgeRepository;
    //         $this->forumMessageRepository = $forumMessageRepository;
    //         $this->userRepository = $userRepository;
    //         $this->entityManager = $entityManager;
    //         $this->managerRegistry = $managerRegistry;
    //     }

    //     public function badgeForumMessageCheck(User $user)
    //     {
    //         $messageCounter = $this->forumMessageRepository->countByUser($user);
    //         dd($messageCounter);
    //         // $user->setForumMessageCounter($messageCounter);
    //         $this->entityManager->persist($user);
    //         $this->entityManager->flush();

    // //         switch ($user->getForumMessageCounter()) {
    // //             case 1:               
    // //         if($user->getForumMessageCounter()>=1){
    // //             $this->awardBadge($user, "Néophyte");

    // //     //         if (!$result) {
    // //     //             return new Response('Badge not awarded');
    // //     //         }
    // //     // dd($user->getFavouriteBadges());
    // //     //         return new Response('Badge awarded successfully');
    // // }
    // // break;
    // // case 3:               
    // // if ($user->getForumMessageCounter()>=3) {
    // //     $this->awardBadge($user, "lvl 3 comment");
    // //     break;
    // // }
    // //             default:
    // //                 # code...
    // //                 break;
    // //         }
    //     }

    //     public function awardBadge(User $user, string $badgeName)
    //     {
    //         $badge = $this->badgeRepository->findOneBy(['name' => $badgeName]);

    //         if (!$badge) {
    //             return false;
    //         }

    //         // $commentsCount = $this->ForumMessageRepository->countByUser($user);

    //         // if ($commentsCount < 20) {
    //         //     return false;
    //         // }

    //         // $userBadge = $this->userRepository->findOneBy(['user' => $user, 'badge' => $badge]);

    //         // if (!$userBadge) {
    //             // $userBadge = new UserRepository($this->managerRegistry);
    //             // $user->setUser($user);
    //             $user->addBadge($badge);
    //         // }

    //         $this->entityManager->persist($user);
    //         $this->entityManager->flush();

    //         return true;
    //     }

    //     // public function setAsFavouriteBadge(User $user, Badge $badge): void
    //     // {
    //     //     $user->addFavouriteBadge($badge);
    //     //     $this->entityManager->flush();
    //     // }

    //     // public function removeFromFavouriteBadge(User $user, Badge $badge): void
    //     // {
    //     //     $user->removeFavouriteBadge($badge);
    //     //     $this->entityManager->flush();
    //     // }
}
