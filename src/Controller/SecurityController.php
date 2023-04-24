<?php

namespace App\Controller;

use App\Repository\VisiteurTempRepository;
use App\Entity\VisiteurTemp;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    
    /**
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/connexion", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request, VisiteurTempRepository $visiterepo): Response
    {
        $ok = $visiterepo->findOneBy(["ip" => $request->getClientIp()]);
        if ( $ok != null) {
            $ok->setAverage(0);
            $this->entityManager->persist($ok);
        } else {
            $visiteur = new VisiteurTemp();
            $visiteur->setIp($request->getClientIp());
            $visiteur->setAverage(0);
            $this->entityManager->persist($visiteur);
        }
        $this->entityManager->flush();
        $user = $this->getUser();
        //  if ($user)  {
        //     $user->setStatut(true);  
        //     $this->entityManager->persist($user);
        //     $this->entityManager->flush();
        //     return $this->redirectToRoute('user');
        //  }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/deconnexion", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
