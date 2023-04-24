<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PreviewController extends AbstractController
{
    /**
     * @Route("/apercu", name="preview")
     */
    public function index(): Response
    {
        return $this->render('preview/index.html.twig');
    }

    /**
     * @Route("/preview/metier", name="preview_metier")
     */
    public function metiers(): Response
    {
        return $this->render('preview/metier/index.html.twig');
    }

    /**
     * @Route("/preview/communication", name="preview_communication")
     */
    public function communication(): Response
    {
        return $this->render('preview/communication/index.html.twig');
    }

    /**
     * @Route("/preview/savoir", name="preview_savoir")
     */
    public function savoirs(): Response
    {
        return $this->render('preview/savoir/index.html.twig');
    }

    /**
     * @Route("/preview/immobilier", name="preview_immobilier")
     */
    public function immobilier(): Response
    {
        return $this->render('preview/immobilier/index.html.twig');
    }

    /**
     * @Route("/preview/design", name="preview_design")
     */
    public function design(): Response
    {
        return $this->render('preview/design/index.html.twig');
    }

    /**
     * @Route("/preview/innovation", name="preview_innovation")
     */
    public function innovations(): Response
    {
        return $this->render('preview/innovation/index.html.twig');
    }
}
