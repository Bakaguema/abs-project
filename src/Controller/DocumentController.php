<?php

namespace App\Controller;

use Doctype;
use App\Entity\User;
use App\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DocumentController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em =$em;
    }
    
    /**
     * @Route("/votre-espace/document", name="documents")
     */
    public function listerDocument(): Response
    {
       // $doc= $this->em->getRepository(Document::class)->findAll();
        // dd($doc);
        return $this->render('document/documents.html.twig');
    }

    /**
     * @Route("/document/supprimer/{id}", name="supprimerDocument")
     */
    
    
    public function supprimerDocument($id): Response
    {

        $doc= $this->em->getRepository(Document::class)->find($id); 
        if($doc && $this->getUser() == $doc->getUser()){
            $this->em->remove($doc);
            $this->em->flush();

        }
        $this->addFlash('success', 'Le document a été supprimé');
         return $this->redirectToRoute('documents');
    }

    /**
     * @Route("/document/apercu/{id}", name="apercuDocument")
     */
    public function apercuDocument($id): Response
    {

        //Récupération de la liste des articles
        $document = $this->em->getRepository(Document::class)->find($id);
        return $this->render('document/documents.html.twig', [
           'document'=>$document
        ]);
    }
}