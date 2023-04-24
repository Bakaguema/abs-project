<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrombinoscopeController extends AbstractController
{
    /**
     * @Route("/trombinoscope", name="trombinoscope")
     */ 
    public function index(): Response
    {
        return $this->render('trombinoscope/index.html.twig');
    }
}