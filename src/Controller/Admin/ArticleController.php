<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Entity\Illustration;
use App\Repository\FriendRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticleController extends AbstractController
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
     * @Route("/admin/article", name="admin_article")
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('admin/article/index.html.twig', [
            'articles' => $articleRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/article/activer/{id}", name="admin_active_article")
     */
    public function active(Article $article): Response
    {
        $article->setActive(!$article->getActive());

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_article');
    }

    /**
     * @Route("/admin/article/editer/{id}", name="admin_edit_article")
     */
    public function edit(Article $article,Request $request): Response
    {

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUsers($this->getUser());
            $article = $form->getData();

            $illustrations = $form->get('illustration')->getData();

            foreach ($illustrations as $illustration) {
                $file = md5(uniqid()) . '.' . $illustration->guessExtension();

                $illustration->move(
                    $this->getParameter('articles_directory'),
                    $file
                );

                $ill = new Illustration();
                $ill->setName($file);
                $article->addIllustration($ill);
            }

            $this->entityManager->persist($article);
            $this->entityManager->flush();

            $this->addFlash('message', "L'article a bien été mis à jour.");

            return $this->redirectToRoute('admin_article');
        }

        return $this->render('admin/article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/article/supprimer/{id}", name="admin_delete_article")
     */
    public function delete(Article $article): Response
    {

        $this->entityManager->remove($article);
        $this->entityManager->flush();

        $this->addFlash('message', "L'article a bien été supprimé");

        return $this->redirectToRoute('admin_article');
    }
}