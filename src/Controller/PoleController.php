<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\PoleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PoleController extends AbstractController
{
    /**
     * @Route("/pole", name="pole")
     */
    public function index(): Response
    {
        return $this->render('pole/index.html.twig');
    }

    /**
     * @Route("/pole/metier", name="pole_metier")
     */
    public function metiers(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request, PoleRepository $poleRepository): Response
    {     
        
        


        $data = $articleRepository->findArticleByPole(1);

        $articles = $paginator->paginate($data, $request->query->getInt('page', 1), 6);

        $description = $poleRepository->findBy(['id' =>1]);

        return $this->render('pole/metier/index.html.twig', [
            'desc' => $description,
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/pole/communication", name="pole_communication")
     */
    public function communication(ArticleRepository $articleRepository,PaginatorInterface $paginator, Request $request,PoleRepository $poleRepository): Response
    {
        
        
        $data = $articleRepository->findArticleByPole(2);


        $articles = $paginator->paginate($data, $request->query->getInt('page', 1), 6);

        $description = $poleRepository->findBy(['id' =>2]);

        return $this->render('pole/communication/index.html.twig', [
            'desc' => $description,
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/pole/savoir", name="pole_savoir")
     */
    public function savoirs(ArticleRepository $articleRepository,PaginatorInterface $paginator, Request $request,PoleRepository $poleRepository): Response
    {
        
        

        $data = $articleRepository->findArticleByPole(3);

        $articles = $paginator->paginate($data, $request->query->getInt('page', 1), 6);
        $description = $poleRepository->findBy(['id' =>3]);

        return $this->render('pole/savoir/index.html.twig', [
            'desc' => $description,
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/pole/immobilier", name="pole_immobilier")
     */
    public function immobilier(ArticleRepository $articleRepository,PaginatorInterface $paginator, Request $request,PoleRepository $poleRepository): Response
    {
        
        

        $data = $articleRepository->findArticleByPole(4);

        $articles = $paginator->paginate($data, $request->query->getInt('page', 1), 6);
        $description = $poleRepository->findBy(['id' =>4]);

        return $this->render('pole/immobilier/index.html.twig', [
            'desc' => $description,
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/pole/design", name="pole_design")
     */
    public function design(ArticleRepository $articleRepository,PaginatorInterface $paginator, Request $request,PoleRepository $poleRepository): Response
    {
        
        

        $data = $articleRepository->findArticleByPole(5);
		

        $articles = $paginator->paginate($data, $request->query->getInt('page', 1), 6);

		
        return $this->render('pole/design/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/pole/innovation", name="pole_innovation")
     */
    public function innovations(ArticleRepository $articleRepository,PaginatorInterface $paginator, Request $request,PoleRepository $poleRepository): Response
    {
        
        

        $data = $articleRepository->findArticleByPole(6);

        $articles = $paginator->paginate($data, $request->query->getInt('page', 1), 6);
        $description = $poleRepository->findBy(['id' =>5]);

        return $this->render('pole/innovation/index.html.twig', [
            'desc' => $description,
            'articles' => $articles,

        ]);
    }
}