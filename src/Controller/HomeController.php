<?php

namespace App\Controller;

use App\Entity\Visiteur;
use App\Entity\VisiteurTemp;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token\Builder;
use App\Repository\FriendRepository;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use App\Repository\ArticleRepository;
use App\Repository\HomeDetailRepository;
use App\Repository\VisiteurRepository;
use App\Repository\VisiteurTempRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request,HomeDetailRepository $homeDetailRepository,VisiteurTempRepository $visiterepo): Response
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

        return $this->render('home/index.html.twig', [
            'homeDetails' => $homeDetailRepository->findAll()
        ]);
    }

    /**
     * @Route("/home/visite/cron", name="visitecronionos")
     */
    public function visitecron(VisiteurTempRepository $visiteurtemprepo)
    {   
        $visitetempo = $visiteurtemprepo->findAll();
        $Visiteur = new Visiteur();
        $Visiteur->setNbvisit(count($visiteurtemprepo->findAll()));
        
        $average = 0;
        foreach ($visitetempo as $moyenne) {
            $average += $moyenne->getAverage();
        }
        if ($average > 0) {
            $average = $average / count($visiteurtemprepo->findAll());
        }
        
        $Visiteur->setAverage((int)$average);
        
        $this->entityManager->persist($Visiteur);
        $this->entityManager->flush();

        $this->entityManager->getConnection()->executeQuery('TRUNCATE visiteur_temp');
    }

    /**
     * @Route("/decouverte", name="home_discover")
     */
    public function discover( ArticleRepository $articleRepository): Response

    {
        $articles = $articleRepository->findAll();

        return $this->render('main/index.html.twig', ['articles' => $articles] );
    }

    /**
     * @Route("localhost:8000")
    */
    public function cookies(): Response
    {
        $response = new Response();
        $response->headers->set('Set-Cookie', 'name=John');
        return $response;
    }
}