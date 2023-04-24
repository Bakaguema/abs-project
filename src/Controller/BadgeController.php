<?php

namespace App\Controller;

use App\Entity\Badge;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BadgeController extends AbstractController
{
      /**
     * @Route("/badge", name="badge")
     */
    public function index(User $user): Response
    {
        $badges = $user->getBadges();

        return $this->render('badge/index.html.twig', [
            'badges' => $badges,
        ]);
    }

    /**
     * @Route("/award-badge", name="award_badge")
     */
    public function awardBadge(User $user, Badge $badge)
    {
//         $result = $this->badgeService->awardBadge($user, $badge);

//         if (!$result) {
//             return new Response('Badge not awarded');
//         }
// dd($user->getFavouriteBadges());
//         return new Response('Badge awarded successfully');
    }
}
