<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CommentController extends AbstractController
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
     * @Route("/admin/commentaire", name="admin_comment")
     */
    public function index(CommentRepository $commentRepository): Response
    {
       
        
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $commentRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/commentaire/activer/{id}", name="admin_active_comment")
     */
    public function active(Comment $comment): Response
    {
       

        $comment->setActive(!$comment->getActive());

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_comment');
    }

    /**
     * @Route("/admin/commentaire/supprimer/{id}", name="admin_delete_comment")
     */
    public function delete(Comment $comment): Response
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();

        $this->addFlash('message', "Le commentaire a bien été supprimé");

        return $this->redirectToRoute('admin_comment');
    }
    
    }


