<?php

namespace App\Controller;

use App\Classes\Mail;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RemarquesController extends AbstractController
{
    /**
     * @Route("/remarques", name="remarques")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $mail = new Mail();
            $content = "Bonjour, <br> Vous avez reçu une remarque ou une suggestion d'un utilisateur de la Generation Boomerang. <br><br>";
            $content .= "Nom de l'utilisateur : " .$formData['fullname']. "<br>Email de l'utilisateur : " .$formData['email']. "<br><br>";
            $content .= "Message de l'utilisateur : " .$formData['content']. "<br><br><hr>";
            $mail->send('contact@generation-boomerang.com' , 'Generation Boomerang', "Un utilisateur vous fais part d'une remarque ou d'une suggestion", $content);
            $this->addFlash('notice', "Merci de nous avoir contacté. Notre équipe va vous répondre dans les meilleurs délais.");
        }

        return $this->render('remarques/remarques.html.twig', [
            'form' => $form->createView()
        ]);
    }
}