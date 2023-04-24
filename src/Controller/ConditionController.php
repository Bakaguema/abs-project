<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConditionController extends AbstractController
{
    /**
     * @Route("/conditions-utilisations", name="condition")
     */
    public function index(): Response
    {
        return $this->render('condition/condition.html.twig');
    }
}
