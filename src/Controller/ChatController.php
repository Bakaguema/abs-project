<?php

namespace App\Controller;

use App\Entity\Attachments;
use App\Entity\Chat;
use App\Entity\Message;
use App\Form\ChatType;
use App\Form\MessageType;
use App\Repository\ChatRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mercure\Authorization;
use Symfony\Component\Mercure\Discovery;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/chat')]
class ChatController extends AbstractController
{

    // #[Route('/{id}', name: 'app_chat_delete', methods: ['POST'])]
    // public function delete(Request $request, Chat $chat, ChatRepository $chatRepository): Response
    // {
    //     if ($this->isCsrfTokenValid('delete' . $chat->getId(), $request->request->get('_token'))) {
    //         $chatRepository->remove($chat);
    //     }

    //     return $this->redirectToRoute('app_chat_index', [], Response::HTTP_SEE_OTHER);
    // }

    #[Route('/create/{userId}', name: 'app_chat_create')]
    public function create(UserRepository $userRepository, ChatRepository $chatRepository, $userId, EntityManagerInterface $em): Response
    {

        $from_user = $this->getUser();
        $to_user = $userRepository->findOneBy(['id' => $userId]);
        $chats = $chatRepository->findByUsers($from_user, $to_user);

        if (empty($chats)) {
            $chat = new Chat();
            $chat->addUser($from_user)
                ->addUser($to_user);
            $chat->setIsArchived(false);
            $em->persist($chat);
            $em->flush();
        } else {
            $chat = $chats[0];
        }
        return $this->redirectToRoute('app_chat_message', ['id' => $chat->getId()]);
    }

    #[Route('/{id}', name: 'app_chat_message', methods: ['GET', 'POST'])]
    public function shareMessage(MessageRepository $messageRepository, UserRepository $userRepository, Chat $chat, Request $request, EntityManagerInterface $em, HubInterface $hub, Authorization $authorization, Discovery $discovery, SluggerInterface $slugger, SerializerInterface $serializer): Response
    {
       

        $session = $request->getSession();
        $user = $this->getUser();
        $CurrentUrl = $request->getUri();
        $userList = $userRepository->getActiveChatsUsers($user);
        $toUser = $userRepository->getTheOtherUser($this->getUser(), $chat->getId());

        // $discovery->addLink($request);
        // $authorization->setCookie($request, $CurrentUrl);

        $data = json_decode($request->getContent(), true);

        if (isset($data['offer']) || isset($data['startWebcam'])) {
            $data['toUserId'] = $toUser[0]->getId();
            $hub->publish(new Update(
                $CurrentUrl,
                json_encode($data),
                true
            ));
    }


        $message = new Message();
        $form = $this->createForm(MessageType::class, $message, [
            // 'method'=>'PUT',
            // 'action'=> $this->generateUrl('app_chat_message',['id'=>$chat->getId()])
        ]);

        $emptyForm = clone $form;

        $form->handleRequest($request);


        $content = $form->get('content')->getData();
        $files = $form->get('attachments')->getData();



        if (($form->isSubmitted() && $form->isValid()) && (isset($content) || isset($file))) {

            if ($files) {

                foreach ($files as $file) {

                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                    try {
                        $file->move(
                            $this->getParameter('message_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    $attach = new Attachments();
                    $attach->setMessage($message);
                    $attach->setName($originalFilename);
                    $attach->setUrl($newFilename);
                    $message->addAttachment($attach);
                }
            }


            $message->setChat($chat);
            $message->setFromId($this->getUser()->getId());

            $message->setToId([$toUser[0]->getId()]);

            $em->persist($message);
            $em->flush();

            $toUserUnreadMessages = count($messageRepository->findUnreadMessagesByUser($toUser));


       
            $jsonMessage = $serializer->serialize($message, 'json', [
                'groups' => ['messagerie'],
            ]);


            $hub->publish(new Update(
                $CurrentUrl,
                $jsonMessage
                // $this->render('chat/message.stream.html.twig', [
                //     'chat' => $chat,
                //     'message' => $message,
                //     'User' => $this->getUser(),
                //     'toUserUnreadMessages' => $toUserUnreadMessages

                // ])
                , true
            ));


            $jsonNotif = $serializer->serialize($message, 'json', [
                'groups' => ['messagerieNotif'],
            ]);

            // Créer un tableau associatif avec la clé et la valeur correspondantes
            $total = array('compteur' => $toUserUnreadMessages);

            $json = json_encode(array_merge($total, json_decode($jsonNotif, true)));

            $hub->publish(
                new Update(
                    $request->getSchemeAndHttpHost() . "/chat",
                    $json, 
                    true

                ),
            );
        }



        $newMessages = $messageRepository->findUnreadMessagesByUser($user);
        if ((isset($newMessages) && !empty($newMessages))) {
            $segments = explode('/', parse_url($request->headers->get('referer'), PHP_URL_PATH));
            $Refererid = end($segments);
            foreach ($newMessages as $mess) {
                if (($chat->getId() == $mess->getChat()->getId()) ||  $Refererid  == $mess->getChat()->getId()) {

                    $mess->setReadAt(new DateTimeImmutable());
                    $em->persist($mess);
                }
            }
            $em->flush();
        }



        $session->set('totalMessage', count($messageRepository->findUnreadMessagesByUser($user)));




        return $this->renderForm('chat/index.html.twig', [
            'chat' => $chat,
            'form' => $emptyForm,
            'convUserLists' => $userList,
            'CurrentUrl' => $CurrentUrl,
            'toUser'=> $toUser

        ]);
    }
}