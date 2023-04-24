<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccessibiliteController extends AbstractController
{
    /**
     * @Route("/accessibilite", name="accessibilite")
     */
    
    public function index(): Response
    {
        return $this->render('accessibilite/accessibilite2.html.twig');
    }
}