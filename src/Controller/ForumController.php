<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Form\ForumType;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Entity\ForumMessage;
use App\Form\ForumMessageType;
use App\Repository\BadgeRepository;
use App\Repository\ForumMessageRepository;
use App\Repository\ForumRepository;
use App\Repository\UserRepository;
use App\Service\BadgeService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Mercure\Discovery;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\WebLink\Link;

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
     * @Route("/discussions", name="forum")
     */
    public function index(ForumRepository $forumRepository, Request $request): Response
    {
        

        $forums = $forumRepository->findBy(['active' => true], ['createdAt' => 'desc']);


        return $this->render('forum/index.html.twig', [
            'forums' => $forums
        ]);
    }

    /**
     * @Route("/discussions/ajout", name="add_forum")
     */
    public function add(Request $request): Response
    {
        
        $forum = new Forum();

        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $forum->setUser($this->getUser());
            $forum->setActive(true);
            $forum = $form->getData();

            $this->entityManager->persist($forum);
            $this->entityManager->flush();

            $this->addFlash('message', 'Le nouveau salon de conversation a bien été ajouté');

            return $this->redirectToRoute('forum');
        }

        return $this->render('forum/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/discussions/editer/{id}", name="edit_forum")
     */
    public function edit(Forum $forum, Request $request): Response
    {
        

        if ($forum->getUser() == $this->getUser() || $this->getUser()->getRoles("ROLES_ADMIN")) {
            $form = $this->createForm(ForumType::class, $forum);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $forum = $form->getData();

                $this->entityManager->persist($forum);
                $this->entityManager->flush();

                $this->addFlash('message', 'Le salon a bien été mise à jour.');

                return $this->redirectToRoute('forum');
            }
        } else {
            return $this->redirectToRoute('forum');
        }

        return $this->render('forum/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/discussions/supprimer/{id}", name="delete_forum")
     */
    public function delete(Forum $forum): Response
    {
        

        $this->entityManager->remove($forum);
        $this->entityManager->flush();

        $this->addFlash('message', "Le salon a bien été supprimé");

        return $this->redirectToRoute('forum');
    }

    /**
     * @Route("/discussions/{id}", name="show_forum")
     */
    public function show($id, ForumRepository $forumRepository, Request $request): Response
    {
        

        $forums = $forumRepository->findOneBy(['id' => $id]);

        //$hubUrl = $this->getParameter('mercure.default_hub');
        $this->addLink($request, new Link('mercure',
         //'http://127.0.0.1:3000' . '/.well-known/mercure')); 
          $request->getSchemeAndHttpHost() . '/.well-known/mercure')); 

        $form = $this->createForm(ForumMessageType::class);

        //$discovery->addLink($request);

        return $this->render('forum/show.html.twig', [
            'id' => $id,
            'forums' => $forums,
            'form' => $form->createView()
        ]);

    }

     /**
     * @Route("/message", name="message")
     */
    public function sendMessage(Request $request,ForumRepository $forumRepository, SerializerInterface $serializer,HubInterface $hub, BadgeRepository $badgeRepository, ForumMessageRepository $forumMessageRepository, UserRepository $userRepository, EntityManagerInterface $entityManagerInterface, ManagerRegistry $managerRegistry ): JsonResponse
    {
        
        $data = \json_decode($request->getContent(), true);
        if (empty($content = $data['content'])) {
            throw new AccessDeniedHttpException('Pas de donnée envoyé');
        }

        $forums = $forumRepository->findOneBy(['id' => $data['channel']]);

        if (!$forums) {
            throw new EntityNotFoundException('Le message doit être dans un salon');
        }
        
        $user = $this ->getUser();
        $message = new ForumMessage();
        $message->setContent($content);
        $message->setForum($forums);
        $message->setActive(true);
        $message->setUser($this->getUser());

            $this->entityManager->persist($message);
            $this->entityManager->flush();
            // $badgeManager = new BadgeService($badgeRepository, $forumMessageRepository, $userRepository, $entityManagerInterface, $managerRegistry);
            // $badgeManager->badgeForumMessageCheck($user);

            $jsonMessage = $serializer->serialize($message, 'json', [
                    'groups' => ['message']
                ]);

                $update = new Update (
                    sprintf( $request->getSchemeAndHttpHost() . '/discussions/%s',
                        $forums->getId())  ,
                    $jsonMessage,
                    true
                );
                
                $hub->publish($update);

        return new JsonResponse(
            $jsonMessage,
            Response::HTTP_OK,
            [],
            true
        );
    }
}
    

