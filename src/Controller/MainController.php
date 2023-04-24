<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Entity\Experience;
use App\Entity\ArticleLike;
use App\Entity\ArticleSave;
use App\Entity\Evenement;
use App\Entity\Signalement;
use App\Form\SignalementType;
use App\Entity\ExperienceLike;
use App\Entity\ExperienceSave;
use App\Form\SearchArticleType;
use App\Repository\UserRepository;

use App\Repository\EmojisRepository;
use App\Repository\FriendRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\UserBlockRepository;
use App\Repository\ExperienceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleLikeRepository;
use App\Repository\ArticleSaveRepository;
use App\Repository\EvenementRepository;
use App\Repository\SignalementRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ExperienceLikeRepository;
use App\Repository\ExperienceSaveRepository;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainController extends AbstractController
{
    private ArticleRepository $articleRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        $this->entityManager = $entityManager;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route("/actualite", name="main")
     */
    public function index(MessageRepository $messageRepository, FriendRepository $friendRepository, ArticleRepository $articleRepository,ArticleLikeRepository $articleLikeRepository,PaginatorInterface $paginator, Request $request): Response
    {
        $session = $request->getSession();
        
        
        $user = $this ->getUser();
        
        $friends = $friendRepository->findAll();
        $friendsUser1 = $friendRepository->findBy(['user1' => $user, 'active' => true ]); 
        $friendsUser2 = $friendRepository->findBy(['user2' => $user, 'active' => true ]);
        $listFriends = count($friendsUser1) + count($friendsUser2);
        
        $session->set('totalMessage', count($messageRepository->findUnreadMessagesByUser($this->getUser())));
        $session->set('totalfriends', $listFriends);
        $session->set('friends', $friends);

        // dd($session->get('friends')) ;

        $like = $articleLikeRepository->findAll();
        $data = $articleRepository->findBy(['active' => true], ['createdAt' => 'desc']);

        $articles = $paginator->paginate($data, $request->query->getInt('page', 1), 6);


        return $this->render('main/index.html.twig', [
            'articles' => $articles,
            'user'=>$user,
            'like'=> $like
        ]);
    }

    /**
     * @Route("/actualite/retour-d'expérience", name="actu-xp")
     */
    public function actuxp(ExperienceRepository $experienceRepository,PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this ->getUser();

        $data = $experienceRepository->findBy(['active' => true], ['createdAt' => 'desc']);
        
        $experiences = $paginator->paginate($data, $request->query->getInt('page', 1), 6);

        return $this->render('main/index2.html.twig', [
            'experiences' => $experiences,
            'user'=> $user
        ]);
    }

    /**
     * @Route("/actualite/my-contact", name="actu-contact")
     */
    public function actufriend(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this ->getUser();

        $data = $articleRepository->findAll();
        
        $articles = $paginator->paginate($data, $request->query->getInt('page', 1), 6);

        return $this->render('main/index3.html.twig', [
            'articles' => $articles,
            'user'=> $user
        ]);
    }

    /**
     * @Route("/articles", name="articles")
     */
    public function articles(ArticleRepository $articleRepository,ArticleLikeRepository $articleLikeRepository,EmojisRepository $emojisRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $user = $this ->getUser();

         $reactions = $emojisRepository->findAll();
        // $users = $userRepository->findBy(['id'=> 'desc']);
        // $users = $articleRepository->findBy(['users' => 'desc']);
        $like = $articleLikeRepository->findAll();
        $searchForm = $this->createForm(SearchArticleType::class);
        $searchForm->handleRequest($request);
        $searchCriteria = $searchForm->getData();

        $articles = $this->articleRepository->search($searchCriteria);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            return $this->render('main/article/search.html.twig', [
                'articles' => $articles,
                'searchForm' => $searchForm->createView()
            ]);
        } else {
            $data = $articleRepository->findBy(['active' => true], ['createdAt' => 'desc']);
            $articles = $paginator->paginate($data, $request->query->getInt('page', 1), 6);
            return $this->render('main/article/index.html.twig', [
                'articles' => $articles,
                'user'=>$user,
                'like'=> $like,
                'searchForm' => $searchForm->createView(),
                'reactions' => $reactions
            ]);
        }
    }

    /**
     * @Route("/article/{slug}", name="article")
     * 
     * 
     */
    public function article( Article $article,$slug,ArticleRepository $articleRepository,UserBlockRepository $UserBlockRepository, CommentRepository $commentRepository, Request $request): Response
    {

        $users = $this ->getUser();

        $article = $articleRepository->findOneBy(['slug' => $slug]);
        $comments = $commentRepository->findBy(['active' => true]);

         //===============================================================================//
        $user = $article->getUsers();
        $user1 = $this->getUser()->getId();
        $userBlocked = $UserBlockRepository->findBy(['user1' => [$user1,$user],
                                                        'user2' => [$user,$user1]  ]);
        //  $userCommentBlocked = $UserBlockRepository->findBy(['user1' => [$user1,$user],
        //                                                 'user2' => [$user,$user1]]);
        $commentsArray = $comments;
        foreach ($comments as $key => $c ){
            foreach ($userBlocked as $ub){
                if ($c->getUser()->getId() == $ub->getUser2()->getId()){
                    unset($commentsArray[$key]);
                }
            }
        };
        
        
         //=================================================================================//
        if (!$article) {
            return $this->redirectToRoute('articles');
        }
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new DateTime());
            $comment->setActive(true);
            $comment->setArticle($article);
            $comment->setUser($this->getUser());

            $parentId = $form->get('parentid')->getData();

            if ($parentId !== null) {
                $parent = $this->entityManager->getRepository(Comment::class)->find($parentId);
            }


            
            

            $comment->setParent($parent ?? null);
            $evenement = new Evenement();
            $evenement->setUser($this->getUser());
            $evenement->setContent('Ajouter');

            $this->entityManager->persist($comment);
            $evenement->setComment($comment);
            $this->entityManager->persist($evenement);
            $this->entityManager->flush();

            $this->addFlash('message', 'Votre commentaire a bien été envoyé.');

            return $this->redirectToRoute('article', ['slug' => $article->getSlug(),
            'userBlockeds'=>$userBlocked
            ]);
        }
        
        
        
        

        return $this->render('main/article/show.html.twig', [
            'article' => $article,
            'commentsArray' => $commentsArray,
            'comments' => $comments,
            'users' => $users,
            
            'userBlockeds'=>$userBlocked,
            'form' => $form->createView()
        ]);
    }


/**
 * @Route("/comment/{id}", name="comment_delete")
 * @ParamConverter("comment", options={"id" = "id"})
 */

    public function delete(Comment $content, EvenementRepository $eventrepo)
    {   
        $user = $this ->getUser();

        $delevent = $eventrepo->findOneBy(['create_at' => $content->getCreatedAt(), 'comment' => $content]);
        if ($delevent == null) {
            $delevent = $eventrepo->findOneBy(['edate' => $content->getCreatedAt(), 'comment' => $content]);
        }
        if ($delevent != null) {
            $delevent->setComment(null);
            $delevent->setContent('Supprimer comment');
            $delevent->setEdate(new DateTime());
    
            $this->entityManager->persist($delevent);
            $this->entityManager->flush();
        }

        $e = $this->getDoctrine()->getManager();
        $e->remove($content);
        $e->flush();

        $this->addFlash('message', 'Votre commentaire a bien été supprimé.');
        return $this->redirectToRoute('articles');
    }

    /**
 * @Route("article/comment/signal/{id}", name="comment_signal")
 *  @ParamConverter("comment", options={"id" = "id"} )
 */
public function signalement($id,Comment $comment,ArticleRepository $articleRepository,CommentRepository $commentRepository, SignalementRepository $SignalementRepository, Request $request): Response

    {
        $user = $this ->getUser();

       
        $signal = new Signalement();
        $form = $this->createForm(SignalementType::class, $signal);
        $form->handleRequest($request);
        
        
        try {
            if ($form->isSubmitted() && $form->isValid()) {

                $comment = $this->getDoctrine()->getRepository(Comment::class)->find($id) ;
                
                $signal->setUser($this->getUser());
                $signal->setComment($comment);
                
            
                $this->entityManager->persist($signal);
                $this->entityManager->flush();
                $this->addFlash('message', 'Votre signalement a bien été envoyé.');
                return $this->redirectToRoute('article', ['slug'=> $comment->getArticle()->getslug()
            ]);
                
                
            }
        } catch (\Throwable $th) {
            $this->addFlash('message', 'Vous avez déjà signalé ce commentaire.');
                return $this->redirectToRoute('article', ['slug'=> $comment->getArticle()->getslug()
            ]);
        }
        
        
            
        
        return $this->render('admin/comment/signalArticleCommentaire.html.twig', [
            'form' =>$form->createView()
        
        ]);
}
    /**
 * @Route("experience/comment/signal/{id}", name="experience_signal")
 *  @ParamConverter("comment", options={"id" = "id"} )
 */
public function signalementExp($id,Comment $comment,ExperienceRepository $experienceRepository,CommentRepository $commentRepository,SignalementRepository $SignalementRepository, Request $request): Response

    {

        $user = $this ->getUser();

       
        $signal = new Signalement();
        $form = $this->createForm(SignalementType::class, $signal);
        $form->handleRequest($request);
        
        
        try {
            if ($form->isSubmitted() && $form->isValid()) {

                $comment = $this->getDoctrine()->getRepository(Comment::class)->find($id) ;
                
                $signal->setUser($this->getUser());
                $signal->setComment($comment);
                
    
                
            
                $this->entityManager->persist($signal);
                $this->entityManager->flush();
                $this->addFlash('message', 'Votre signalement a bien été envoyé.');
                return $this->redirectToRoute('experience', ['slug'=> $comment->getExperience()->getslug()]);
                
                
            }
        } catch (\Throwable $th) {
            $this->addFlash('message', 'Vous avez déjà signalé ce commentaire.');
                return $this->redirectToRoute('experience', ['slug'=> $comment->getExperience()->getslug()
            ]);
        }
        
        
            
        
        return $this->render('admin/comment/signalExperienceSignal.html.twig', [
            'form' =>$form->createView()]);
}







/**
 * @Route("article/comment/edit/{id}", name="edit_comment")
 * @ParamConverter("comment", options={"id" = "id"} )
 *
 */

    public function edit(EvenementRepository $eventrepo ,$id, Comment $comment, Request $request,ArticleRepository $articleRepository,CommentRepository $commentRepository): Response{
        $user = $this ->getUser();
        
        $commentaire = $this->getDoctrine()->getRepository(Comment::class)->find($id);
        $edit = $this->getDoctrine()->getRepository(Comment::class)->find($id);
        $form = $this->createForm(CommentType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $evenement = $eventrepo->findOneBy(['create_at' => $commentaire->getCreatedAt(), 'comment' => $commentaire]);
            if ($evenement == null) {
                $evenement = $eventrepo->findOneBy(['edate' => $commentaire->getCreatedAt(), 'comment' => $commentaire]);
            }
            if ($evenement != null) {
                $evenement->setContent('Editer');
                $evenement->setEdate(new DateTime());
                $this->entityManager->persist($evenement);
            }

            $commentaire->setUser($this->getUser());
            $commentaire = $form->getData();
            $commentaire->setCreatedAt(new DateTime());
            $commentaire->setActive(true);
            $commentaire->getId($commentaire->getId());
            $parentId = $form->get('parentid')->getData();
            if ($parentId !== null) {
                $parent = $this->entityManager->getRepository(Comment::class)->find($parentId);
            }
            
            $commentaire->setParent($parent ?? null);

            
            $this->entityManager->persist($commentaire);
            $this->entityManager->flush();

            $this->addFlash('message', 'Votre commentaire a bien été modifié.');

            return $this->redirectToRoute('article' , [ 'slug'=> $commentaire->getArticle()->getslug()]);
        }
        return $this->render('admin/comment/editArticle.html.twig', [
            
            'comments' => $commentaire,
            'form' => $form->createView()
        ]);
        

    }





    /**
     * @Route("/articles/{id}/like", name="article_like")
     * @param Article $article
     * @param ArticleLikeRepository $articleLikeRepository
     * @return Response
     */
    public function like(Article $article, ArticleLikeRepository $articleLikeRepository): Response
    {
        $user = $this ->getUser();        

        if (!$user) return $this->json([
            'code' => 403,
            'message' => "Pas connectée"
        ], 403);
        
        if ($article->isLikedbyUser($user)) {
            $like = $articleLikeRepository->findOneBy([
                'article' => $article,
                'user' => $user
            ]);
        
            $this->entityManager->remove($like);
            $this->entityManager->flush();
        
            return $this->json([
            'code' => 200,
                'message' => 'like supprimé',
                'likes' => $articleLikeRepository->count(['article' => $article]),
            ]);
        }
        
        $like = new ArticleLike();
        $like->setArticle($article);
        $like->setUser($user);
        
        $this->entityManager->persist($like);
        $this->entityManager->flush();
        
        return $this->json([
            'code' => 200,
            'message' => 'like ajouté',
            'likes' => $articleLikeRepository->count(['article' => $article]),
        ]);
    }
    

    /**
     * @Route("/articles/{id}/save", name="article_save")
     * @param Article $article
     * @param ArticleSaveRepository $articleSaveRepository
     * @return Response
     */
    public function save(Article $article, ArticleSaveRepository $articleSaveRepository): Response
    {
        $user = $this ->getUser();

        if (!$user) return $this->json([
            'code' => 403,
            'message' => 'Pas connectée'
        ], 403);

        if ($article->isSavedByUser($user)) {
            $save = $articleSaveRepository->findOneBy([
                'article' => $article,
                'user' => $user
            ]);

            $this->entityManager->remove($save);
            $this->entityManager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'sauvegarde supprimé',
                'saves' => $articleSaveRepository->count(['article' => $article])
            ]);
        }

        $save = new ArticleSave();
        $save->setArticle($article);
        $save->setUser($user);

        $this->entityManager->persist($save);
        $this->entityManager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'sauvegarde ajouté',
            'likes' => $articleSaveRepository->count(['article' => $article])
        ]);
    }

    /**
     * @Route("/retour-experiences", name="experiences")
     */
    public function experiences(ExperienceRepository $experienceRepository,PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this ->getUser();
        $data = $experienceRepository->findBy(['active' => true], ['createdAt' => 'desc']);
        
        $experiences = $paginator->paginate($data, $request->query->getInt('page', 1), 6);

        return $this->render('main/experience/index.html.twig', [
            'experiences' => $experiences,
            'user'=> $user
        ]);
    }

    /**
     * @Route("/retour-experience/{slug}", name="experience")
     */
    public function experience(Experience $experience,$slug,UserBlockRepository $UserBlockRepository, ExperienceRepository $experienceRepository, CommentRepository $commentRepository, Request $request): Response
    {   
        $users = $this ->getUser();
        $experience = $experienceRepository->findOneBy(['slug' => $slug]);
        $comments = $commentRepository->findBy(['active' => true]);
        
        //===============================================================================//
        $user = $experience->getUser();
        $user1 = $this->getUser()->getId();
        $userBlocked = $UserBlockRepository->findBy(['user1' => [$user1,$user],
                                                        'user2' => [$user,$user1]  ]);
        // dd($user);
        //=================================================================================//

        if (!$experience) {
            return $this->redirectToRoute('experiences');
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new DateTime());
            $comment->setActive(true);
            $comment->setExperience($experience);
            $comment->setUser($this->getUser());

            $parentId = $form->get('parentid')->getData();

            if ($parentId !== null) {
                $parent = $this->entityManager->getRepository(Comment::class)->find($parentId);
            }

            $comment->setParent($parent ?? null);

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $this->addFlash('message', 'Votre commentaire a bien été envoyé.');

            return $this->redirectToRoute('experience', ['slug' => $experience->getSlug(),
            'userBlockeds'=>$userBlocked
        ]);
           
        }

        return $this->render('main/experience/show.html.twig', [
            'experience' => $experience,
            'comments' => $comments,
            'userBlockeds'=>$userBlocked,
            'users' => $users,
            'form' => $form->createView()
    ]);
    }

    /**
     * @Route("/experiences/{id}/like", name="experience_like")
     * @param Experience $experience
     * @param ExperienceLikeRepository $experienceLikeRepository
     * @return Response
     */
    public function expLike(Experience $experience,ExperienceLikeRepository $experienceLikeRepository): Response
    {
        $user = $this ->getUser();


        if (!$user) return $this->json([
            'code' => 403,
            'message' => "Pas connectée"
        ], 403);

        if ($experience->isLikedbyUser($user)) {
            $like = $experienceLikeRepository->findOneBy([
                'experience' => $experience,
                'user' => $user
            ]);

            $this->entityManager->remove($like);
            $this->entityManager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'like supprimé',
                'likes' => $experienceLikeRepository->count(['experience' => $experience])
            ]);
        }

        $like = new ExperienceLike();
        $like->setExperience($experience);
        $like->setUser($user);

        $this->entityManager->persist($like);
        $this->entityManager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'like ajouté',
            'likes' => $experienceLikeRepository->count(['experience' => $experience]),

        ]);
    }

    /**
     * @Route("/experiences/{id}/save", name="experience_save")
     * @param Experience $experience
     * @param ExperienceSaveRepository $experienceSaveRepository
     * @return Response
     */
    public function experienceSave(Experience $experience, ExperienceSaveRepository $experienceSaveRepository): Response
    {
        $user = $this ->getUser();


        if (!$user) return $this->json([
            'code' => 403,
            'message' => "Pas connectée"
        ], 403);

        if ($experience->isSavedByUser($user)) {
            $save = $experienceSaveRepository->findOneBy([
                'experience' => $experience,
                'user' => $user
            ]);

            $this->entityManager->remove($save);
            $this->entityManager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'sauvegarde supprimé',
                'likes' => $experienceSaveRepository->count(['experience' => $experience])
            ]);
        }

        $save = new ExperienceSave();
        $save->setExperience($experience);
        $save->setUser($user);

        $this->entityManager->persist($save);
        $this->entityManager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'sauvegarde ajouté',
            'likes' => $experienceSaveRepository->count(['experience' => $experience])
        ]);
    }
    /**
 * @Route("experience/comment/edit/{id}", name="experienceEdit")
 * @ParamConverter("comment", options={"id" = "id"} )
 *
 */

public function experiencesEdit($id, Comment $comment,ExperienceRepository $experienceRepository,Request $request,ArticleRepository $articleRepository,CommentRepository $commentRepository): Response{
        
    $user = $this ->getUser();

    $commentaire = $this->getDoctrine()->getRepository(Comment::class)->find($id);
    $edit = $this->getDoctrine()->getRepository(Comment::class)->find($id);
    $form = $this->createForm(CommentType::class, $commentaire);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

        
        $commentaire->setUser($this->getUser());
        $commentaire = $form->getData();
        $commentaire->setCreatedAt(new DateTime());
        $commentaire->setActive(true);
        $commentaire->getId($commentaire->getId());
        $parentId = $form->get('parentid')->getData();
        if ($parentId !== null) {
            $parent = $this->entityManager->getRepository(Comment::class)->find($parentId);
        }
        
        $commentaire->setParent($parent ?? null);

        $this->entityManager->persist($commentaire);
        $this->entityManager->flush();

        $this->addFlash('message', 'Votre commentaire a bien été modifié.');

        return $this->redirectToRoute('experience' , [ 'slug'=> $commentaire->getExperience()->getslug()]);
    }
    return $this->render('admin/comment/editExperience.html.twig', [
        
        'comments' => $commentaire,
        'form' => $form->createView()
    ]);
    
}



/**
     * @Route("experience/comment/delet/{id}", name="deleteExperience")
     * @ParamConverter("comment", options={"id" = "id"})
     */
    public function delet(Comment $comment, Signalement $signalement)
    {   
        $user = $this ->getUser();

        $e = $this->getDoctrine()->getManager();
        $e->remove($signalement);
        $e->remove($comment);
        $e->flush();
        $this->addFlash('message', 'Votre commentaire a bien été supprimé.');
        return $this->redirectToRoute('experiences');
    }

    /**
     * @Route("/admin/signalement", name="admin_signalement")
     *
     * 
     */
    public function admin_signal(SignalementRepository $SignalementRepository): Response

    {   $user = $this ->getUser();
        return $this->render('admin/signalement/liste.html.twig', [
        'signalsok' => $SignalementRepository->findBy(['view' => true]),
        'signalsatt' => $SignalementRepository->findBy(['view' => false])
    ]);
    }

    /**
     * @Route("/admin/signalement/{id}", name="admin_signalement_id")
     * 
     */
    public function admin_signal_id(Signalement $signalement): Response

    {  
        $signalement->setView(true);
        $id = $signalement->getId();
        $this->addFlash('message', "Le signalement avec l'id $id est bien résolu.");
        $this->entityManager->persist($signalement);
        $this->entityManager->flush();
        
        return $this->redirectToRoute('admin_signalement');
    }

    /**
     * @Route("/admin/signalement/att/{id}", name="admin_signalement_attente")
     * 
     */
    public function admin_signal_attente(Signalement $signalement): Response

    {  
        $signalement->setView(false);
        $id = $signalement->getId();
        $this->addFlash('message', "Le signalement avec l'id $id est bien en attente.");
        $this->entityManager->persist($signalement);
        $this->entityManager->flush();
        
        return $this->redirectToRoute('admin_signalement');
    }
}

