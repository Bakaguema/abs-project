<?php

namespace App\Controller;

use Exception;
use ReflectionMethod;
use Throwable;
use App\Entity\City;
use App\Entity\User;
use App\Entity\Work;
use App\Classes\Mail;
use App\Entity\Media;
use App\Form\DocType;
use App\Entity\Friend;
use App\Form\UserType;
use App\Form\WorkType;
use App\Entity\Article;
use App\Entity\Purpose;
use App\Form\MediaType;
use App\Entity\Document;
use App\Form\ProfilType;
use App\Form\ArticleType;
use App\Form\PurposeType;
use App\Entity\Experience;
use App\Entity\ArticleLike;
use App\Entity\ArticleSave;
use App\Entity\Illustration;
use App\Form\ChangeMailType;
use App\Form\ExperienceType;
use App\Form\PartnerBioType;
use App\Form\SearchUserType;
use App\Form\ChangePasswordType;
use App\Entity\CodeRedeem;
use App\Entity\Evenement;
use App\Form\UserCodeRedeemType;
use Gedmo\Sluggable\Util\Urlizer;
use App\Repository\UserRepository;
use App\Repository\WorkRepository;
use App\Repository\MediaRepository;
use App\Repository\FriendRepository;
use App\Repository\ArticleRepository;
use App\Repository\BadgeRepository;
use App\Repository\PurposeRepository;
use App\Repository\BlockUserRepository;
use App\Repository\CodeRedeemRepository;
use App\Repository\EvenementRepository;
use App\Repository\UserBlockRepository;
use App\Repository\ExperienceRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private FriendRepository $friendRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, FriendRepository $friendRepository)
    {
        $this->entityManager = $entityManager;
        $this->friendRepository = $friendRepository;
    }


    /**
     * @Route("/votre-espace/", name="user")
     */
    public function progressBar(ArticleRepository $articleRepository, ExperienceRepository $experienceRepository): Response
    {
        $user = $this->getUser();

        $articles = $articleRepository->findBy(['users' => $user], ['id' => 'desc']);
        $experiences = $experienceRepository->findBy(['user' => $user], ['id' => 'desc']);

        $pBar = 0;
        if ($user) {
            $pBar += 20;
        }
        if ($user->getBio() == null) {
            $pBar += 0;
        } else {
            $pBar += 10;
        }
        if ($user->getPicture() == null) {
            $pBar += 0;
        } else {
            $pBar += 10;
        }
        if ($user->getProfil() == null) {
            $pBar += 0;
        } else {
            $pBar += 10;
        }
        if (($user->getInstagram() || $user->getFacebook() || $user->getTwitter() || $user->getLinkedin() || $user->getPinterest() || $user->getSnapchat() || $user->getTiktok() || $user->getYoutube()) == null) {
            $pBar += 0;
        } else {
            $pBar += 5;
        }
        if ($user->getSituation() == null) {
            $pBar += 0;
        } else {
            $pBar += 10;
        }
        if ($user->getMetier() == null) {
            $pBar += 0;
        } else {
            $pBar += 5;
        }
        if ($user->getObjectif() == null) {
            $pBar += 0;
        } else {
            $pBar += 10;
        }
        if ($user->getArticles() == null) {
            $pBar += 0;
        } else {
            $pBar += 10;
        }
        if ($user->getExperiences() == null) {
            $pBar += 0;
        } else {
            $pBar += 10;
        }
        //   dd($pBar);
        return $this->render('user/index.html.twig', [
            'pbar' => $pBar,
            'user' => $user,
            'articles' => $articles,
            'experiences' => $experiences
        ]);
    }

    /**
     * @Route("/votre-espace/realisations/", name="user_realisations")
     */

    public function realisations(ArticleRepository $articleRepository, ExperienceRepository $experienceRepository): Response
    {

        $user = $this->getUser();

        $articles = $articleRepository->findBy(['users' => $user], ['id' => 'desc']);
        $experiences = $experienceRepository->findBy(['user' => $user], ['id' => 'desc']);
        $badges = $user->getBadge()->toArray();

        return $this->render('user/badge/index.html.twig', [
            'articles' => $articles,
            'experiences' => $experiences,
            'badges' => $badges
        ]);
    }

    /**
     * @Route("/votre-espace/profil/", name="profils")
     */
    public function profil(UserBlockRepository $UserBlockRepository): Response
    {
        $user = $this->getUser();
        $userBlocked = $UserBlockRepository->findAll();
        $friends = $this->friendRepository->findAll();

        $friendsUser1 = $this->friendRepository->findBy(['user1' => $user, 'active' => true]);
        $friendsUser2 = $this->friendRepository->findBy(['user2' => $user, 'active' => true]);
        $listFriends = count($friendsUser1) + count($friendsUser2);

        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'userBlockeds' => $userBlocked,
            'listFriends' => $listFriends,
            'friends' => $friends

        ]);
    }

    /**
     * @Route("/votre-espace/contactlist", name="is_accept")
     */
    public function contact(Request $request): Response
    {
        $session = $request->getSession();
        $user = $this->getUser();
        $friends = $this->friendRepository->findAll();
        $friendsUser1 = $this->friendRepository->findBy(['user1' => $user, 'active' => true ]); 
        $friendsUser2 = $this->friendRepository->findBy(['user2' => $user, 'active' => true ]);
        $listFriends = count($friendsUser1) + count($friendsUser2);
        $session->set('friends', $friends);
        $session->set('totalfriends', $listFriends);


        return $this->render('user/friend/isaccept.html.twig');
    }

    /**
     * @Route("/votre-espace/contact/waiting", name="my_contact")
     */
    public function isAccept(Request $request): Response
    {
        $friends = $this->friendRepository->findAll();
        $request->getSession()->set('friends', $friends);

        return $this->render('user/friend/waiting.html.twig');
    }
    /**
     * @Route("/votre-espace/contact/sending", name="contact_sending")
     */
    public function friendSend(Request $request): Response
    {
        $friends = $this->friendRepository->findAll();
        $request->getSession()->set('friends', $friends);


        return $this->render('user/friend/sending.html.twig');
    }

    /**
     * @Route("/votre-espace/contact/supprimer/{id}", name="delete_friend")
     */
    public function deleteFriend(Friend $friend, $id, Request $request): Response
    {

        $user = $this->getUser();
        $friend = $this->friendRepository->findOneBy(['id' => $id]);
        $this->entityManager->remove($friend);
        $this->entityManager->flush();
        $friends = $this->friendRepository->findAll();
        $request->getSession()->set('friends', $friends);

        $friendsUser1 = $this->friendRepository->findBy(['user1' => $user, 'active' => true]);
        $friendsUser2 = $this->friendRepository->findBy(['user2' => $user, 'active' => true]);
        $listFriends = count($friendsUser1) + count($friendsUser2);
        $request->getSession()->set('totalfriends', $listFriends);

        $this->addFlash('message', "Le contact a bien été supprimé");

        return $this->redirectToRoute('is_accept');
    }

    /**
     * @Route("/votre-espace/contact/accept/{id}", name="accept_friend")
     */
    public function acceptFriend(Friend $friend, $id): Response
    {
        $user = $this->getUser();
        $friend = $this->friendRepository->findOneBy(['id' => $id]);
        $friend->setActive(true);
        $this->entityManager->flush();
        $friends = $this->friendRepository->findAll();

        $this->addFlash('message', "Le contact a bien été accepté");

        $friendsUser1 = $this->friendRepository->findBy(['user1' => $user, 'active' => true]);
        $friendsUser2 = $this->friendRepository->findBy(['user2' => $user, 'active' => true]);
        $listFriends = count($friendsUser1) + count($friendsUser2);

        return $this->redirectToRoute('my_contact', [
            'listFriends' => $listFriends,
            'friends' => $friends
        ]);
    }



    /**
     * @Route("/votre-espace/search", name="search")
     */
    public function search( UserRepository $userRepository, PaginatorInterface $paginator, Request $request, UserBlockRepository $UserBlockRepository): Response
    {

        $userBlocked = $UserBlockRepository->findAll();
        $searchForm = $this->createForm(SearchUserType::class);
        $searchForm->handleRequest($request);
        $region = null;
        $departement = null;
        $ville=null;
        $searchCriteria = $searchForm->getData();
        // Donnéees non mapées
        $region = $searchForm->get("region")->getData();
        if($region!==null){
            $departement = $searchForm->get("departement")->getData();
            }

        if($departement!==null){
        $ville = $searchForm->get("city")->getData();
        }
        $users = $userRepository->search($searchCriteria, $region, $departement, $ville);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            return $this->render('user/search.html.twig', [

                'userBlockeds' => $userBlocked,
                'users' => $users,
                'searchForm' => $searchForm->createView()
            ]);
        } else {

            return $this->render('user/search.html.twig', [
                'userBlockeds' => $userBlocked,
                'users' => $users,
                'searchForm' => $searchForm->createView()

            ]);
        }
    }
    /**
     * @Route("/votre-espace/editer/photo-profil", name="user_edit_picture")
     */
    public function editPicture( Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadFile = $form['picture']->getData();

            if ($uploadFile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/profiles';

                $originalFileName = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFileName) . '-' . uniqid() . '.' . $uploadFile->guessExtension();

                $uploadFile->move(
                    $destination,
                    $newFilename
                );

                $user->setPicture($newFilename);
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('message', 'Votre photo de profil à bien été mise à jour.');



            return $this->redirectToRoute('user_edit_picture');
        }

        return $this->render('user/profil/picture.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/votre-espace/editer/document", name="user_edit_document")
     */
    public function addDocument( Request $request): Response
    {
        $user = $this->getUser();

        $doc = new Document();

        $form = $this->createForm(DocType::class, $doc);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            $date = new \DateTimeImmutable();
            $uploadFile = $form['document']->getData();
            $doc->setUser($this->getUser());
            $user = $this->getUser();
            if ($uploadFile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/profiles/document';

                //$file->move($directory, $file->getClientOriginalName());
                $originalFileName = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFileName) . $uploadFile->guessExtension();

                $uploadFile->move(
                    $destination,
                    $newFilename
                );

                $doc->setName($newFilename);
                $doc->setCreatedAt($date);
                $user->setDocument($newFilename);
            }

            $this->entityManager->persist($doc, $user);
            $this->entityManager->flush();

            $this->addFlash('message', 'Votre document a bien été ajouté.');

            return $this->redirectToRoute('user_edit_document');
        }

        return $this->render('user/profil/document.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/votre-espace/editer/bio", name="user_edit_bio")
     */
    public function editBio( Request $request, UserRepository $userRepository): Response
    {

        $user = $this->getUser();
        $status = $userRepository->findAll();
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('message', 'Vos informations ont bien été mise à jour.');

            return $this->redirectToRoute('user_edit_bio');
        }

        return $this->render('user/profil/bio.html.twig', [
            'form' => $form->createView(),
            'status' => $status
        ]);
    }

    /**
     * @Route("/votre-espace/editer/partner", name="partner_edit_bio")
     */
    public function editPartner( Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(PartnerBioType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('message', 'Vos informations ont bien été mise à jour.');

            return $this->redirectToRoute('partner_edit_bio');
        }

        return $this->render('user/profil/partner.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/votre-espace/modifier-mot-de-passe", name="edit_password")
     */
    public function editPassword( Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData();

            if ($hasher->isPasswordValid($user, $old_pwd)) {
                $new_pwd = $form->get('new_password')->getData();
                $password = $hasher->hashPassword($user, $new_pwd);

                $user->setPassword($password);
                $this->entityManager->flush();

                $this->addFlash('message', 'Votre mot de passe à bien été mis à jour.');
                return $this->redirectToRoute('user');
            }
        }

        return $this->render('user/password/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/votre-espace/modifier-adresse-mail", name="edit_mail")
     * @param string $token
     * @throws Exception
     */
    public function editMail( Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangeMailType::class, $user);

        $form->handleRequest($request);


        try {
            if ($form->isSubmitted() && $form->isValid()) {

                $old_email = $form->get('email')->getData();
                $password = $form->get('password')->getData();

                if ($hasher->isPasswordValid($user, $password)) {

                    $new_email = $form->get('new_email')->getData();
                    $user->setEmail($new_email);
                    $user->setActive(0);
                    $user->setToken($this->generateToken());
                    $user->getToken();
                    $url = $this->generateUrl('confirm_email', ['token' => $user->getToken()]);

                    $mail = new Mail();
                    $mail->send(
                        $user->getEmail(),
                        $user->getFirstname(),
                        'Merci de confirmer votre email',
                        'Bonjour ' . $user->getFirstname() . '<br>
                            Afin de confirmer votre changement d\'adresse email, merci de <a href="http://beta2.generation-boomerang.com' . $url . '">cliquer ici</a>.'
                    );

                    $this->entityManager->flush();
                    $user->setActive(1);
                    return $this->redirectToRoute('success_register');

                } else $this->addFlash('message', 'Mauvais mot de passe.');
            }
        } catch (\Throwable $th) {
            $this->addFlash('message', 'Cet adresse mail est déjà utilisé');
            return $this->render('user/mail/edit.html.twig', [
                'form' => $form->createView()
            ]);
        }

        return $this->render('user/mail/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }



    /**
     * @Route("/votre-espace/interview", name="user_interview")
     */
    public function interview( MediaRepository $mediaRepository): Response
    {
        return $this->render('user/media/index.html.twig', [
            'medias' => $mediaRepository->findAll()
        ]);
    }


    /**
     * @Route("/votre-espace/interview/ajout", name="add_interview")
     */
    public function addinterview( Request $request): Response
    {
        $media = $this->getUser();

        $media = new Media();
        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $media->setUser($this->getUser());
            $media->setActive(false);
            $uploadFile = $form['video']->getData();

            if ($uploadFile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/interview';

                //$file->move($directory, $file->getClientOriginalName());
                $originalFileName = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFileName) . '-' . uniqid() . '.' . $uploadFile->guessExtension();

                $uploadFile->move(
                    $destination,
                    $newFilename
                );
                $media->setVideo($newFilename);
            }


            $this->entityManager->persist($media);
            $this->entityManager->flush();

            $this->addFlash('message', 'Votre interview a bien été créé.');

            return $this->redirectToRoute('add_interview');
        }

        return $this->render('user/media/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/votre-espace/interview/edit/{id}", name="edit_interview")
     */
    public function editinterview( Media $media, Request $request): Response
    {

        $form = $this->createForm(MediaType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $media->setUser($this->getUser());
            $uploadFile = $form['video']->getData();

            if ($uploadFile) {
                $destination = $this->getParameter('media_directory');

                //$file->move($directory, $file->getClientOriginalName());
                $originalFileName = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFileName) . '-' . uniqid() . '.' . $uploadFile->guessExtension();

                $uploadFile->move(
                    $destination,
                    $newFilename
                );
                $media->setVideo($newFilename);
            }
            $this->entityManager->persist($media);
            $this->entityManager->flush();

            $this->addFlash('message', 'Votre interview a bien été modifié.');

            return $this->redirectToRoute('add_interview');
        }

        return $this->render('user/media/edit.html.twig', [
            'media' => $media,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/votre-espace/interview/supprimer/{id}", name="delete_interview")
     */
    public function deleteInterview( media $media): Response
    {
        $this->entityManager->remove($media);
        $this->entityManager->flush();

        $this->addFlash('message', 'Votre interview a bien été supprimé');
        return $this->redirectToRoute('add_interview');
    }

    /**
     * @Route("/votre-espace/articles", name="user_article")
     */
    public function article(ArticleRepository $articleRepository,  PaginatorInterface $paginator, Request $request): Response
    {
        $users = $this->getUser();

        $data = $articleRepository->findBy(['users' => $users], ['id' => 'desc']);


        $articles = $paginator->paginate($data, $request->query->getInt('page', 1), 6);


        return $this->render('user/article/index.html.twig', [
            'articles' => $articles,
            'users' => $users,
            'data' => $data
        ]);
    }

    /**
     * @Route("/votre-espace/articles/ajout", name="add_article")
     */
    public function addArticle( Request $request): Response
    {

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUsers($this->getUser());
            $article->setActive(false);
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
            $evenement = new Evenement();
            $evenement->setUser($this->getUser());
            $evenement->setArticle($article);
            $evenement->setcontent("Ajouter");
            $this->entityManager->persist($evenement);
            $this->entityManager->flush();

            $this->addFlash('message', 'Votre article a bien été créé.');
            $this->addFlash('success', 'Felicitation ! Vous venez de débloqué un badge article.');

            return $this->redirectToRoute('user_article');
        }

        return $this->render('user/article/add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/votre-espace/articles/editer/{id}", name="edit_article")
     */
    public function editArticle(EvenementRepository $eventrepo ,Article $article,  Request $request): Response
    {
        $user = $this->getUser();
        

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
            $evenement = $eventrepo->findOneBy(["create_at" => $article->getcreatedat(), "article" => $article]);
            if ($evenement == null) {
                $evenement = $eventrepo->findOneBy(["edate" => $article->getcreatedat(), "article" => $article]);
            }
            if ($evenement != null) {
                $evenement->setEdate(new DateTime());
                $evenement->setContent('Editer');
                $this->entityManager->persist($evenement);
            }
            $this->entityManager->flush();

            $this->addFlash('message', 'Votre article a bien été mis à jour.');

            return $this->redirectToRoute('user_article');
        }

        return $this->render('user/article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/votre-espace/articles/supprimer/{id}", name="delete_article")
     */
    public function deleteArticle(EvenementRepository $eventrepo ,Article $article): Response
    {
        $user = $this->getUser();

        $evenement = $eventrepo->findOneBy(["create_at" => $article->getcreatedat(), "article" => $article]);
        if ($evenement == null) {
            $evenement = $eventrepo->findOneBy(["edate" => $article->getcreatedat(), "article" => $article]);
        }
        if ($evenement != null) {
            $evenement->setEdate(new DateTime());
            $evenement->setContent('Supprimer article');
            $evenement->setArticle(null);
            $this->entityManager->persist($evenement);
        }
        $this->entityManager->remove($article);
        $this->entityManager->flush();

        $this->addFlash('message', 'Votre article a bien été supprimé');
        return $this->redirectToRoute('user_article');
    }

    /**
     * @Route("/votre-espace/supprime/article/illustration/{id}", name="delete_articles_illustration")
     */
    public function deleteArticlesIllustration( Illustration $illustration): Response
    {
        $this->entityManager->remove($illustration);
        $this->entityManager->flush();

        $this->addFlash('message', 'Votre image a bien été supprimée');
        return $this->redirectToRoute('user_article');
    }

    /**
     * @Route("/votre-espace/experiences", name="user_experience")
     */
    public function experience(ExperienceRepository $experienceRepository,  PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();

        $data = $experienceRepository->findBy(['active' => true], ['id' => 'desc']);
        $experienceById = $experienceRepository->findBy(['user' => $user], ['id' => 'desc']);

        $experiences = $paginator->paginate($data, $request->query->getInt('page', 1), 6);


        return $this->render('user/experience/index.html.twig', [
            'experiences' => $experiences,
            'user' => $user,
            'experienceById' => $experienceById
        ]);
    }

    /**
     * @Route("/votre-espace/experiences-utilisateur", name="user_user_experience")
     */
    public function userExperience(ExperienceRepository $experienceRepository): Response
    {
        return $this->render('user/experience/user.html.twig', [
            'experiences' => $experienceRepository->findAll()
        ]);
    }

    /**
     * @Route("/votre-espace/experiences/ajout", name="add_experience")
     */
    public function addExperience( Request $request): Response
    {
        $experience = new Experience();
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $experience->setUser($this->getUser());
            $experience->setActive(true);
            $experience = $form->getData();

            $illustrations = $form->get('illustration')->getData();

            foreach ($illustrations as $illustration) {
                $file = md5(uniqid()) . '.' . $illustration->guessExtension();

                $illustration->move(
                    $this->getParameter('experiences_directory'),
                    $file
                );

                $ill = new Illustration();
                $ill->setName($file);
                $experience->addIllustration($ill);
            }

            $this->entityManager->persist($experience);
            $evenement = new Evenement();
            $evenement->setContent("Ajouter");
            $evenement->setExperience($experience);
            $evenement->setUser($this->getUser());
            $this->entityManager->persist($evenement);
            $this->entityManager->flush();

            $this->addFlash('message', "Votre retour d'expérience a bien été créé.");
            $this->addFlash('success', 'Felicitation ! Vous venez de débloqué un badge experience.');

            return $this->redirectToRoute('user_experience');
        }

        return $this->render('user/experience/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/votre-espace/experiences/editer/{id}", name="edit_experience")
     */
    public function editExperience(EvenementRepository $eventrepo,Experience $experience,  Request $request): Response
    {
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $experience->setUser($this->getUser());
            $experience = $form->getData();

            $illustrations = $form->get('illustration')->getData();

            foreach ($illustrations as $illustration) {
                $file = md5(uniqid()) . '.' . $illustration->guessExtension();

                $illustration->move(
                    $this->getParameter('experiences_directory'),
                    $file
                );

                $ill = new Illustration();
                $ill->setName($file);
                $experience->addIllustration($ill);
            }

            $this->entityManager->persist($experience);
            $evenement = $eventrepo->findOneBy(["create_at" => $experience->getcreatedat(), "experience" => $experience]);
            if ($evenement == null) {
                $evenement = $eventrepo->findOneBy(["edate" => $experience->getcreatedat(), "experience" => $experience]);
            }
            if ($evenement != null) {
                $evenement->setEdate(new DateTime());
                $evenement->setContent('Editer');
                $this->entityManager->persist($evenement);
            }
            $this->entityManager->flush();

            $this->addFlash('message', "Votre retour d'expérience a bien été mis à jour.");

            return $this->redirectToRoute('user_experience');
        }

        return $this->render('user/experience/edit.html.twig', [
            'experience' => $experience,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/votre-espace/experience/supprimer/{id}", name="delete_experience")
     */
    public function deleteExperience(EvenementRepository $eventrepo,Experience $experience): Response
    {
        $experience->setActive(false);

        $evenement = $eventrepo->findOneBy(["create_at" => $experience->getcreatedat(), "experience" => $experience]);
        if ($evenement == null) {
            $evenement = $eventrepo->findOneBy(["edate" => $experience->getcreatedat(), "experience" => $experience]);
        }
        if ($evenement != null) {
            $evenement->setEdate(new DateTime());
            $evenement->setContent('Supprimer experience');
            $evenement->setExperience(null);
            $this->entityManager->persist($evenement);
        }
        $this->entityManager->flush();

        $this->addFlash('message', "Votre retour d'expérience a bien été supprimé");

        return $this->redirectToRoute('user_experience');
    }

    /**
     * @Route("/votre-espace/supprime/experience/illustration/{id}", name="delete_experiences_illustration")
     */
    public function deleteExperiencesIllustration( Illustration $illustration): Response
    {
        $this->entityManager->remove($illustration);
        $this->entityManager->flush();

        $this->addFlash('message', 'Votre image a bien été supprimée');
        return $this->redirectToRoute('user_experience');
    }

    /**
     * @Route("/votre-espace/annonces-user", name="user_annonce")
     */
    public function userAnnonce(WorkRepository $workRepository,  PurposeRepository $purposeRepository): Response
    {
        $users = $this->getUser();

        return $this->render('user/work/index.html.twig', [
            'works' => $workRepository->findAll(),
            'purposes' => $purposeRepository->findAll(),
            'users' => $users
        ]);
    }

    /**
     * @Route("/votre-espace/annonce/editer/{id}", name="edit_work")
     */
    public function editWork(EvenementRepository $eventrepo, Work $work,  Request $request): Response
    {


        $form = $this->createForm(WorkType::class, $work);
        $form['revenu']->setData($work->getrevenu());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $work->setUsers($this->getUser());
            $work = $form->getData();
            $evenement = $eventrepo->findOneBy(["create_at" => $work->getcreatedat(), "hiring" => $work]);
            if ($evenement == null) {
                $evenement = $eventrepo->findOneBy(["edate" => $work->getcreatedat(), "hiring" => $work]);
            }
            if ($evenement != null) {
                $evenement->setEdate(new DateTime());
                $evenement->setContent('Editer');
                $this->entityManager->persist($evenement);
            }
            $work->setCreatedAt(new DateTime());


            $this->entityManager->persist($work);
            $this->entityManager->flush();

            $this->addFlash('message', "Votre annonce a bien été mise à jour.");

            return $this->redirectToRoute('user_annonce');
        }

        return $this->render('user/work/edit.html.twig', [
            'works' => $work,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/votre-espace/candidat/editer/{id}", name="edit_purpose")
     */
    public function editPurpose(EvenementRepository $eventrepo ,Purpose $purpose,  Request $request): Response
    {
        $form = $this->createForm(PurposeType::class, $purpose);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $purpose->setUsers($this->getUser());
            $purpose = $form->getData();
            

            $evenement = $eventrepo->findOneBy(["create_at" => $purpose->getcreatedat(), "purpose" => $purpose]);
            if ($evenement == null) {
                $evenement = $eventrepo->findOneBy(["edate" => $purpose->getcreatedat(), "purpose" => $purpose]);
            }
            if ($evenement != null) {
                $evenement->setEdate(new DateTime());
                $evenement->setPurpose(null);
                $evenement->setContent('Editer purpose');
                $this->entityManager->persist($evenement);
            }
            $this->entityManager->persist($purpose);
            $this->entityManager->flush();

            $this->addFlash('message', "Votre annonce a bien été mise à jour.");

            return $this->redirectToRoute('user_annonce');
        }

        return $this->render('user/purpose/edit.html.twig', [
            'purposes' => $purpose,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/votre-espace/annonce/supprimer/{id}", name="delete_annonce")
     */
    public function deleteAnnonce(EvenementRepository $eventrepo, Work $work): Response
    {
        $evenement = $eventrepo->findOneBy(["create_at" => $work->getcreatedat(), "hiring" => $work]);
        if ($evenement == null) {
            $evenement = $eventrepo->findOneBy(["edate" => $work->getcreatedat(), "hiring" => $work]);
        }
        if ($evenement != null) {
            $evenement->setEdate(new DateTime());
            $evenement->setHiring(null);
            $evenement->setContent('Supprimer hiring');
            $this->entityManager->persist($evenement);
        }

        $this->entityManager->remove($work);
        $this->entityManager->flush();

        $this->addFlash('message', "Votre annonce a bien été supprimée");

        return $this->redirectToRoute('user_annonce');
    }

    /**
     * @Route("/votre-espace/candidat/supprimer/{id}", name="delete_purpose")
     */
    public function deletePurpose(EvenementRepository $eventrepo , Purpose $purpose): Response
    {
        $evenement = $eventrepo->findOneBy(["create_at" => $purpose->getcreatedat(), "purpose" => $purpose]);
        if ($evenement == null) {
            $evenement = $eventrepo->findOneBy(["edate" => $purpose->getcreatedat(), "purpose" => $purpose]);
        }
        if ($evenement != null) {
            $evenement->setEdate(new DateTime());
            $evenement->setPurpose(null);
            $evenement->setContent('Supprimer purpose');
            $this->entityManager->persist($evenement);
        }
        $this->entityManager->remove($purpose);
        $this->entityManager->flush();

        $this->addFlash('message', "Votre annonce a bien été supprimée");

        return $this->redirectToRoute('user_annonce');
    }

    /**
     * @return string
     * @throws Exception
     */
    private function generateToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=', []);
    }
    /**
     * @Route("/votre-espace/Utilisateur-bloqué", name="block_user")
     */
    public function showBlockUser( UserBlockRepository $UserBlockRepository): Response
    {
        $user = $this->getUser();

        return $this->render('user/block/isBlocked.html.twig', [
            'userBlocks' => $UserBlockRepository->findAll(),
            'user' => $user
        ]);
    }
    /**
     * @Route("/votre-espace/Redeem-Code", name="code_redeem_user")
     */
    public function RedeemCodeUser( Request $request, CodeRedeemRepository $CodeRedeem): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserCodeRedeemType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $code = $form->get("Code")->getData();
            $codesql = $CodeRedeem->findOneBy(['Code' => $code]);
            if ($codesql == null) {
                $this->addFlash('message', "Erreur, Le Code saisi est introuvable.");
            } else {
                $utilisation = $codesql->getUtilisation();
                if ($utilisation == 0) {
                    $this->addFlash('message', "Erreur, Le Code saisi n'est plus valable.");
                } else {
                    $utilisation -= 1;
                    $codesql->setUtilisation($utilisation);
                    $user->setRoles(explode(" ", 'ROLE_SUB'));
                    $codesql->addUser($user);
                    $this->entityManager->persist($codesql);
                    $evenement = new Evenement();
                    $evenement->setUser($this->getUser());
                    $evenement->setCoupon($codesql);
                    $evenement->setContent("Enregistrer");
                    $this->entityManager->persist($evenement);
                    $this->entityManager->flush();
                    $this->addFlash('notice', "Votre code a été validé, Vous êtes maintenant Abonnées. Merci de vous reconnectez.");
                    return $this->redirectToRoute('app_login');
                }
            }
        };
        return $this->render('/user/coupon/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/votre-espace/Utilisateur-debloqué/{id}", name="deblock_user")
     */
    public function deblockUser($id,  UserBlockRepository $UserBlockRepository): Response
    {

        $user = $this->getUser();
        $e = $this->getDoctrine()->getManager();
        $userDeblock = $UserBlockRepository->findOneBy(['id' => $id]);
        $e->remove($userDeblock);
        $this->entityManager->flush();

        $this->addFlash('message', "L'utilisateur a été débloqué");

        return $this->render('user/block/isBlocked.html.twig', [
            'userBlocks' => $UserBlockRepository->findAll(),
            'user' => $user
        ]);
    }
}
