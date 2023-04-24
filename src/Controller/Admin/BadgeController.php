<?php

namespace App\Controller\Admin;

use App\Entity\Badge;
use App\Form\CreateBadgeType;
use App\Form\EditBadgeType;
use App\Form\GrantBadgeType;
use App\Form\RevokeBadgeType;
use App\Repository\BadgeRepository;
use App\Repository\FriendRepository;
use App\Repository\UserRepository;
use App\Service\BadgeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;

class BadgeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private BadgeService $badgeService;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, BadgeService $badgeService)
    {
        $this->entityManager = $entityManager;
        $this->badgeService = $badgeService;
    }

    /**
     * @Route("/admin/badge", name="admin_badge")
     */
    public function index(RequestStack $requestStack, BadgeRepository $badgeRepository): Response
    {
        $badge = new Badge();
        $request = $requestStack->getMainRequest();
        $createBadgeForm = $this->createForm(CreateBadgeType::class, $badge);
        $editBadgeForm = $this->createForm(EditBadgeType::class);
        $grantBadgeForm = $this->createForm(GrantBadgeType::class);
        $revokeBadgeForm = $this->createForm(RevokeBadgeType::class);

        $createBadgeForm->handleRequest($request);

        if ($createBadgeForm->isSubmitted() && $createBadgeForm->isValid()) {
            return $this->badgeService->handleBadgeFormData($createBadgeForm, 'handleValidForm');
            $this->addFlash('message', 'Le nouveau badge a bien été ajouté.');
        } else {
            $errors = $createBadgeForm->getErrors(true, false);

            foreach ($errors as $error) {
                $errorMessage = $error->getMessage();
                $this->addFlash('error', $errorMessage);
            }
        }

        $editBadgeForm->handleRequest($request);

        if ($editBadgeForm->isSubmitted() && $editBadgeForm->isValid()) {
            return $this->badgeService->handleBadgeFormData($editBadgeForm, 'handleValidEditForm');
            $this->addFlash('message', 'Le badge a bien été modifié.');
        } else {
            $errors = $editBadgeForm->getErrors(true, false);

            foreach ($errors as $error) {
                $errorMessage = $error->getMessage();
                $this->addFlash('error', $errorMessage);
            }
        }


        return $this->render('admin/badge/index.html.twig', [
            'createBadgeForm' => $createBadgeForm->createView(),
            'editBadgeForm' => $editBadgeForm->createView(),
            'grantBadgeForm' => $grantBadgeForm->createView(),
            'revokeBadgeForm' => $revokeBadgeForm->createView(),
            'badges' => $badgeRepository->findAll()
        ]);
    }

    /**
     * @Route("admin/badge/grant", name="admin_badge_grant")
     */
    public function grant(Request $request, UserRepository $userRepository, BadgeRepository $badgeRepository): JsonResponse
    {
        $form = $this->createForm(GrantBadgeType::class);

        $data = $request->request->all();
        $userId = $data['grant_badge']['user'];
        $badgeId = $data['grant_badge']['badge'];

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->find($userId);
            $badge = $badgeRepository->find($badgeId);

            if (!$user || !$badge) {
                return new JsonResponse(['success' => false, 'message' => 'Valeur erronée.']);
            } else {
                $userName = $user->getFirstName() . ' ' . $user->getLastName();
                if ($user->getBadges()->contains($badge)) {
                    return new JsonResponse(['success' => false, 'message' => $userName . ' possède déjà ce badge']);
                } else {
                    $user->addBadge($badge);

                    $this->entityManager->persist($user);
                    $this->entityManager->flush($user);
                    return new JsonResponse(['success' => true, 'message' => 'Badge correctement attribué à ' . $userName . ' !']);
                }
            }
        } else {
            return new JsonResponse([
                'success' => false,
                'message' => 'Formulaire invalide'
            ]);
        }
    }

       /**
     * @Route("admin/badge/revoke", name="admin_badge_revoke")
     */
    public function revoke(Request $request, UserRepository $userRepository, BadgeRepository $badgeRepository): JsonResponse
    {
        $form = $this->createForm(RevokeBadgeType::class);

        $data = $request->request->all();
        $userId = $data['revoke_badge']['user'];
        $badgeId = $data['revoke_badge']['badge'];

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->find($userId);
            $badge = $badgeRepository->find($badgeId);

            if (!$user || !$badge) {
                return new JsonResponse(['success' => false, 'message' => 'Valeur erronée.']);
            } else {
                $userName = $user->getFirstName() . ' ' . $user->getLastName();
                $badgeName = $badge->getName();

                if (!$user->getBadges()->contains($badge)) {
                    return new JsonResponse(['success' => false, 'message' => $userName . ' ne possède pas ou plus ce badge']);
                } else {
                    $user->removeBadge($badge);

                    $this->entityManager->persist($user);
                    $this->entityManager->flush($user);
                    return new JsonResponse(['success' => true, 'message' => 'Le badge "'.$badgeName.'" correctement attribué à ' . $userName . ' !']);
                }
            }
        } else {
            return new JsonResponse([
                'success' => false,
                'message' => 'Formulaire invalide'
            ]);
        }
    }

    /**
     * @Route("/admin/badge/{id}/del", name="admin_badge_del")
     */
    public function delBadge(Badge $badge)
    {
        $badgeName = $badge->getName();
        if ($this->entityManager->contains($badge)) {
            $this->entityManager->remove($badge);
            $this->entityManager->flush();
            return new JsonResponse([
                'success' => true,
                'message' => 'Le badge "' . $badgeName . '" a été supprimé avec succès !'
            ]);
        } else {
            return new JsonResponse([
                'success' => false,
                'message' => 'Le badge "' . $badgeName . '" n\'existe pas ou plus !'
            ]);
        }
    }

    /**
     * @Route("/admin/badgesList", name="admin_get_badgesList", methods={"GET"})
     */
    public function getBadgesList(BadgeRepository $badgeRepository): JsonResponse
    {
        $badges = $badgeRepository->findAll();

        $response = [];

        foreach ($badges as $badge) {
            $response[] = [
                'id' => $badge->getId(),
                'name' => $badge->getName(),
                'category' => $badge->getBadgeCategory(),
            ];
        }
        return $this->json($response);
    }

     /**
     * @Route("/admin/userBadgesList/{userId}", name="admin_get_userBadgesList", methods={"GET"})
     */
    public function getUserBadgesList(BadgeRepository $badgeRepository, UserRepository $userRepository, int $userId): JsonResponse
    {
        $user = $userRepository->find($userId);
        $badges = $badgeRepository->findOwnedByUser($user);

        $response = [
            'badges' => array_map(function(Badge $badge) {
                return [
                    'id' => $badge->getId(),
                    'name' => $badge->getName(),
                ];
            }, $badges),
        ];

        return $this->json($response);
    }

    /**
     * @Route("/admin/getBadge/{id}", name="admin_getBadge", methods={"GET"})
     */
    public function getBadge(Badge $badge, SerializerInterface $serializer): JsonResponse
    {
        if (!$badge) {
            throw $this->createNotFoundException('Badge not found');
        }
    
        return new JsonResponse([
            'name' => $badge->getName(),
            'description' => $badge->getDescription(),
            'badgeCategory' => $badge->getBadgeCategory(),
            'threshold' => $badge->getThreshold(),
            'image' => $badge->getImage(),
        ]);
    }
}