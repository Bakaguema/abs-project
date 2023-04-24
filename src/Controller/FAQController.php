<?php

namespace App\Controller;

use App\Repository\FAQRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FAQController extends AbstractController
{
    /**
     * @Route("/foire-aux-questions", name="faq")
     */
    public function index(FAQRepository $FAQRepository): Response
    {

        return $this->render('faq/index.html.twig', [
            'faqs' => $FAQRepository->findAll()
        ]);
    }
}
