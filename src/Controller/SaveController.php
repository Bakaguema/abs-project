<?php

namespace App\Controller;

use App\Repository\ArticleSaveRepository;
use App\Repository\ExperienceSaveRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SaveController extends AbstractController
{
    /**
     * @Route("/favoris", name="save")
     */
    public function experience(): Response
    {

        return $this->render('save/index.html.twig');
    }
}
