<?php

namespace App\Controller\Admin;

use App\Entity\Forum;
use App\Entity\Comment;
use App\Repository\ForumRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ForumController extends AbstractController
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
     * @Route("/admin/discussions", name="admin_forum")
     */
    public function index(ForumRepository $forumRepository): Response
    {
        return $this->render('admin/forum/index.html.twig', [
            'forums' => $forumRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/discussions/activer/{id}", name="admin_active_forum")
     */
    public function active(Forum $forum): Response
    {
        $forum->setActive(!$forum->getActive());

        $this->entityManager->persist($forum);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_forum');
    }

    /**
     * @Route("/admin/discussions/supprimer/{id}", name="admin_delete_forum")
     */
    public function delete(Forum $forum): Response
    {
        $this->entityManager->remove($forum);
        $this->entityManager->flush();

        $this->addFlash('message', "Le salon de discussions a bien été supprimé");

        return $this->redirectToRoute('admin_forum');
    }
}