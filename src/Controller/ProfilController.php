<?php

namespace App\Controller;

use Throwable;
use App\Entity\Friend;
use App\Form\FriendType;
use App\Entity\BlockUser;
use App\Entity\UserBlock;
use App\Form\BlockUserType;
use App\Form\UserBlockType;
use App\Form\SearchUserType;
use App\Form\SearchFriendType;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\UserRepository;
use App\Repository\FriendRepository;
use App\Repository\BlockUserRepository;
use App\Repository\UserBlockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ProfilController extends AbstractController
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
     * @Route("/profil/{id}", name="profil")
     * 
     */
    public function index(UserBlockRepository $UserBlockRepository, UserRepository $userRepository, FriendRepository $friendRepository, $id, Request $request): Response
    {
        $user = $this->getUser();
        $user = $userRepository->findOneBy(['id' => $id]); 
        $user1 = $this->getUser()->getId(); 
        $friends = $friendRepository->findBy(['user1' => $user]);
        $friendsUser1 = $friendRepository->findBy(['user1' => $user, 'active' => true]);
        $friendsUser2 = $friendRepository->findBy(['user2' => $user, 'active' => true]);
        $listFriends = count($friendsUser1) + count($friendsUser2);
        $userBlocked = $UserBlockRepository->findBy([
            'user1' => [$user1, $user],
            'user2' => [$user, $user1]
        ]);
        $Useralreadyblocked = false;
        foreach ($userBlocked as $userblock) {
           if($userblock->getUser2()==$user){
                $Useralreadyblocked = true;
                $relationbloqued = $userblock;
           }
        }
        $UserBlock = new UserBlock;
        $formBlock = $this->createForm(UserBlockType::class, $UserBlock);
        $formBlock->handleRequest($request);
        $friend = new Friend;
        $form = $this->createForm(FriendType::class, $friend);
        $form->handleRequest($request);

        $iduser = $user->getid();
        $idself = $this->getuser()->getid();
        $friend2 = new Friend;
        $friend2 = $friendRepository->search($iduser);
        $friendok = false;
        for ($i = 0; $i < count($friend2); $i++) {
            if ($friend2[$i]->getuser1()->getid() === $idself || $friend2[$i]->getuser1()->getid() === $iduser) {
                if ($friend2[$i]->getuser2()->getid() === $idself || $friend2[$i]->getuser2()->getid() === $iduser) {
                    $friendok = true;
                    break;
                }
            }
        }
        $user = $userRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(FriendType::class, $friend);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $friend->setuser1($this->getUser());
            $friend->setuser2($user);
            $friend->setActive(false);
            $friend = $form->getData();
            $this->entityManager->persist($friend);
            $this->entityManager->flush();
            $this->addFlash('message', 'Vous avez envoyé une demande de mise en relation');

            return $this->redirectToRoute('contact_sending', [
                'listFriends' => $listFriends,
                'friends' => $friends
            ]);
        }
        if($formBlock->isSubmitted() && $formBlock->isValid()){
            if($Useralreadyblocked){
                $this->entityManager->remove($relationbloqued);
                $this->entityManager->flush();
                $this->addFlash('message','Vous avez débloqué cet utilisateur');
            } else {
                $UserBlock->setUser1($this->getUser());
                $UserBlock->setUser2($user);
                $UserBlock->setActive(1);

                $UserBlock = $formBlock->getData();
                $this->entityManager->persist($UserBlock);
                $this->entityManager->flush();
                $this->addFlash('message','Vous avez bloqué cet utilisateur');
            }

            return $this->redirectToRoute('contact_sending', [
                'listFriends' => $listFriends,
                'friends' => $friends
            ]);
        }

        return $this->render('profil/index.html.twig', [
            'isblocked' => $Useralreadyblocked,
            'friend2' => $friendok,
            'user' => $user,
            'user1' => $user1,
            'userBlockeds'=>$userBlocked,
            'friend' => $friend,
            'friends' => $friends,
            'userBlocks' => $UserBlock,
            'formBlock' => $formBlock->createView(),
            'form' => $form->createView(),
            'listFriends' => $listFriends
        ]);
    }




    /**
     * @Route("/profil/{id}/friend", name="profilfriend")
     */
    public function ListFriend(UserRepository $userRepository, FriendRepository $friendRepository, $id, Request $request): Response
    {
        $user = $this->getUser();
        $user = $userRepository->findOneBy(['id' => $id]);
        $searchForm = $this->createForm(SearchFriendType::class);
        $searchForm->handleRequest($request);
        $searchCriteria = $searchForm->getData();
        $friends = $friendRepository->search($searchCriteria);

        $friendsUser1 = $friendRepository->findBy(['user1' => $user, 'active' => true]);
        $friendsUser2 = $friendRepository->findBy(['user2' => $user, 'active' => true]);
        $listFriends = count($friendsUser1) + count($friendsUser2);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            return $this->render('user/friend/list.html.twig', [
                'friends' => $friends,
                'user' => $user,
                $options['current_id'] = $user->getId(),
                'searchForm' => $searchForm->createView(),
                'listFriends' => $listFriends
            ]);
        } else {
            return $this->render('user/friend/list.html.twig', [
                'friends' => $friends,
                'user' => $user,
                $options['current_id'] = $user->getId(),
                'searchForm' => $searchForm->createView(),
                'listFriends' => $listFriends
            ]);
        }
    }
}
