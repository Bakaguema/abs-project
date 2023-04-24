<?php

namespace App\Controller;

use DateTime;
use App\Entity\Media;
use App\Entity\Comment;
use App\Form\MediaType;
use App\Form\CommentType;
use App\Repository\MediaRepository;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MediaController extends AbstractController
{
    /**
     * @Route("/media", name="medias")
     */
    public function index(MediaRepository $mediaRepository): Response
    {
        return $this->render('media/index.html.twig', [
            'medias' => $mediaRepository->findAll()
        ]);
    }
    /**
     * @Route("/media/{slug}", name="media")
     */
    public function interview($slug, MediaRepository $mediaRepository,  CommentRepository $commentRepository, Request $request): Response
    {
        $media = $mediaRepository->findOneBy(['slug' => $slug]);
        $comments = $commentRepository->findBy(['active' => true]);


        if (!$media) {
            return $this->redirectToRoute('media');
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new DateTime());
            $comment->setActive(true);
            $comment->setMedia($media);
            $comment->setUser($this->getUser());

            $parentId = $form->get('parentid')->getData();

            if ($parentId !== null) {
                $parent = $this->entityManager->getRepository(Comment::class)->find($parentId);
            }

            $comment->setParent($parent ?? null);

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $this->addFlash('message', 'Votre commentaire a bien été envoyé.');

            return $this->redirectToRoute('media', ['slug' => $media->getSlug()
        ]);
        }

        return $this->render('media/show.html.twig', [
            'media' => $media,
            'comments' => $comments,
            'form' => $form->createView()
        ]);
    }
    

}