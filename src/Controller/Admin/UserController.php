<?php

namespace App\Controller\Admin;


use App\Entity\User;
use App\Classes\Mail;
use App\Entity\Article;
use App\Entity\CodeRedeem;
use App\Form\UnbanType;
use App\Form\AdminUserType;
use App\Form\UserCreate;
use App\Form\CodeRedeemType;
use App\Form\CodeRedeemType2;
use App\Service\PasswordGen;
use App\Service\CodeRedeemGen;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\UserRepository;
use App\Repository\CodeRedeemRepository;
use App\Repository\FriendRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Knp\Component\Pager\PaginatorInterface;


class UserController extends AbstractController
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
     * @Route("/admin/utilisateur", name="admin_user")
     */
    public function index(UserRepository $userRepository): Response
    {

        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/utilisateur/editer/{id}", name="admin_edit_user")
     */
    public function edit(User $user, Request $request): Response
    {
       
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('message', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView()        
        ]);
    }

    /**
     * @Route("/admin/utilisateur/supprimer/{id}", name="admin_delete_user")
     */
    public function delete(User $user): Response
    {
        
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->addFlash('message', "L'utilisateur a bien été supprimé");

        return $this->redirectToRoute('admin_user');
    }

/**
     * @Route("/admin/utilisateur/ban" , name="admin_user-ban")
     * 
     */
    public function banned(UserRepository $userRepository): Response
    {    
        $deban = $this->getDoctrine()->getManager();
        $deban->getFilters()->disable('softdeleteable');
       
        return $this->render('admin/user/banned.html.twig', [
            'users' => $userRepository->findAll()
            
        ]);
    }
   


/**
     * @Route("/admin/utilisateur/unban/{id}", name="admin_user-unban")
     * 
     * 
     */
    public function unbanned($id,UserRepository $userRepository): Response
    {  
        $deban = $this->getDoctrine()->getManager();
        $deban->getFilters()->disable('softdeleteable');
        // $id = $_GET[{id}];
        // $deban= $userRepository->findOneBy(['id' => $id]);
        // $userBan = $this->getDoctrine()->getRepository(User::class)->findBy(['id' => '{id}']);
        $userBan = $userRepository->findOneBy(['id' => $id]);
        $userBan->setDeletedAt(null);
        // $user->getUser($user);
        // $user->setDeletedAt(null);
        $this->entityManager->flush();

        $this->addFlash('message', "L'utilisateur a été débanni");

        return $this->redirectToRoute('admin_user-ban');
    }

    
    /**
     * @Route("/admin/utilisateur/creation", name="admin_user-create")
     * 
     * 
     */
    public function create(Request $request,PasswordGen $passwordGenerator,UserPasswordHasherInterface $hasher): Response
    {  
        $user = new User();
       
        $form = $this->createForm(UserCreate::class, $user);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user2 = new User();
            $passwordnohash = $passwordGenerator->GenerateRandomStrongPassword(20);
            $password = $passwordnohash;
            $password = $hasher->hashPassword($user2, $passwordnohash);
            $user2 = $form->getData();
            $user2->setPassword($password);
            $user2->setRoles(explode(" ",'ROLE_SUB'));
            $user2->setConditions(1);
            $user2->setPole(6);
            $user2->setPicture('default.jpg');
            $user2->setToken($this->generateToken());
            $this->entityManager->persist($user2);
            $this->entityManager->flush();
            $mail = $user2->getEmail();
            $this->addFlash('message', "Votre e-mail : $mail");
            $this->addFlash('message', "Votre mot de passe : $passwordnohash");
            $url = $this->generateUrl('confirm_email', [ 'token' => $user2->getToken() ]);
            $mail = new Mail();
            $mail->send(
                    $user2->getEmail(),
                    $user2->getFirstname(),
                    'Merci de confirmer votre email',
                    'Bonjour '.$user2->getFirstname().'<br>
                    Afin de confirmer votre inscription merci de <a href="http://beta2.generation-boomerang.com'.$url.'">cliquer ici</a>.'
                )
            ;
            return $this->redirectToRoute('admin_user-creatafter');
        }

        return $this->render('/admin/user/usercreate.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/utilisateur/creation/ok", name="admin_user-creatafter")
     * 
     * 
     */
    public function aftercreate(Request $request): Response
    {  
        $user = $this->getUser();

        $form = $this->createForm(UserCreate::class, $user);

        $form->handleRequest($request);

        return $this->render('/admin/user/success.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/utilisateur/creation/code", name="admin_user-code")
     * 
     * 
     */
    public function coderedeem(Request $request,CodeRedeemGen $CodeGenerator): Response
    {  
        $form = $this->createForm(CodeRedeemType::class);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $Coderedeem = $form-> getData();
            $Codegenerer = $CodeGenerator->GenerateCodeRedeem(10);
            $Utilisation = $form-> get("Utilisation") -> getData();
            $nom = $form->get('Nom') -> getData();
            $Coderedeem->setUtilisation($Utilisation);
            $Coderedeem->setCode($Codegenerer);
            $Coderedeem->setNom($nom);
            $Coderedeem->setMaxUsage($Utilisation);
            $this->addFlash('message', "Le code a bien été créer $Codegenerer.");
            $this->entityManager->persist($Coderedeem);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_user-dashboard');
        }
        
        return $this->render('/admin/user/coderedeem.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/utilisateur/creation/code/dashboard", name="admin_user-dashboard")
     * 
     * 
     */
    public function dashboardcode(CodeRedeemRepository $Coderedeem,Request $request, PaginatorInterface $paginator): Response
    {  
        $donnees = $Coderedeem->findAll();
        $listcode = $paginator->paginate($donnees,$request->query->getInt('page', 1),6);
        
        return $this->render('/admin/user/dashboardredeem.html.twig', [
            'Codes' => $listcode
        ]);
    }

    /**
     * @Route("/admin/utilisateur/creation/code/dashboard/{id}", name="admin_coupon-edit")
     */
    public function coderedeemedit(user $user,UserRepository $userRepository,CodeRedeem $code, Request $request, CodeRedeemRepository $CodeRepository): Response
    {
        
        $codelist = $userRepository->findBy(['redeem' => $code]);
        $donnee = [];
        $com = 0;
        $article = 0;
        $fav = 0;
        $favxp = 0;
        $news = 0;
        $xps = 0;
        $channel = 0;
        $msg = 0;
        $like = 0;
        $likexps = 0;
        for ($i=0; $i < count($codelist); $i++) { 
            $com += count($codelist[$i]->getComments());
            $article += count($codelist[$i]->getarticles());
            $fav += count($codelist[$i]->getSaves());
            $favxp += count($codelist[$i]->getExpSaves());
            $xps += count($codelist[$i]->getExperiences());
            $channel += count($codelist[$i]->getForums());
            $msg += count($codelist[$i]->getForumMessages());
            $like += count($codelist[$i]->getLikes());
            $likexps += count($codelist[$i]->getExpLikes());
            if ($codelist[$i]->getIsSubscribe() == true) {
                $news += 1;
            }
        }
        $donnee['nbarticle'] = $article ;
        $donnee['nbxps'] = $xps ;
        $donnee['like'] = $like ;
        $donnee['likexps'] = $likexps ;
        $donnee['nbcommentaire'] = $com;
        $donnee['channel'] = $channel;
        $donnee['message'] = $msg;
        $donnee['favoris'] = $fav ;
        $donnee['favorisExp'] = $favxp ;
        $donnee['newsletter'] = $news ;

        $form = $this->createForm(CodeRedeemType2::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $codeidlist = $CodeRepository->findBy(['id' => $code]);
            $nomcode = $codeidlist[0]->getNom();
            $Utilisationcode = $codeidlist[0]->getUtilisation();
            $Utilisation = $form->get("Utilisation")->getData();
            $MaxCode = $codeidlist[0]->getMaxUsage();
            $MaxCode += $Utilisation;
            $codeidlist[0]->setMaxUsage($MaxCode);
            $this->addFlash('message', "Vous venez d'ajouter $Utilisation utilisations au code $nomcode");
            $Utilisation += $Utilisationcode;
            $codeidlist[0]->setUtilisation($Utilisation);
            $this->entityManager->persist($codelist[0]);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_coupon-edit', ['id' => $code->getId()]);
        }

        return $this->render('admin/user/codedetail.html.twig', [
            'Donnee' => $donnee,
            'Code' => $code,
            'form' => $form->createView()        
        ]);
    }
    /**
     * @Route("/admin/statistique", name="admin_stats")
     */
    public function indexstat(): Response
    {
        return $this->render('admin/statistique/index.html.twig');
    }


    /**
     * @return string
     * @throws Exception
     */
    private function generateToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    /**
 * @Route("/users/{id}/profile-picture", name="user_profile_picture")
 */
public function userProfilePicture(User $user)
{
    $response = new Response();
    $response->headers->set('Content-Type', 'image/jpeg');
    $response->setContent($user->getPicture());
    return $response;
}
}