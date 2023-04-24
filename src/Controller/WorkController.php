<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Work;
use App\Form\WorkType;
use App\Entity\Purpose;
use App\Form\PurposeType;
use App\Form\SearchArticleType;
use App\Repository\WorkRepository;
use App\Repository\PurposeRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WorkController extends AbstractController

{   private WorkRepository $workRepository;
    private EntityManagerInterface $entityManager;
     /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, WorkRepository $workRepository,PurposeRepository $purposeRepository)
    {
       
        $this->entityManager = $entityManager;
        $this->workRepository = $workRepository;
        $this->purposeRepository = $purposeRepository;

       
    }

    /** 
    * @Route("/work", name= "works")
    */
    
    public function index(WorkRepository $workRepository,Request $request): Response
    {   
    
        $searchForm = $this->createForm(SearchArticleType::class);
        $searchForm->handleRequest($request);
        $searchCriteria = $searchForm->getData();
        

        $works = $this->workRepository->search($searchCriteria);
        $searchForm->isSubmitted() && $searchForm->isValid();
            return $this->render('work/index.html.twig', [
                'works' => $works,
                'searchForm' => $searchForm->createView()
            ]);

}

    /** 
    * @Route("/purpose", name= "purposes")
    */

    public function candidats(PurposeRepository $purposeRepository,PaginatorInterface $paginator, Request $request): Response{
    

    $searchForm = $this->createForm(SearchArticleType::class);
    $searchForm->handleRequest($request);
    $searchCriteria = $searchForm->getData();

    $purposes = $purposeRepository->search($searchCriteria);
    if($searchForm->isSubmitted() && $searchForm->isValid()){
        return $this->render('work/purposelist.html.twig', [
            'purposes' => $purposes,
            'searchForm' => $searchForm->createView(),
        ]);
    }
    else{
        $data = $purposeRepository->findBy(['active' => true], ['created_at' => 'desc']);

        $purposes = $paginator->paginate($data, $request->query->getInt('page', 1), 6);
        return $this->render('work/purposelist.html.twig',[
            'purposes'=>$purposeRepository->findall(),
            'searchForm' => $searchForm->createView()
        ]); 
    }
}


    /** 
    * @Route("/work/hiring", name= "hiring")
    */
    public function Hiring(Request $request): Response
    {
        
            $work = new Work();
            $form = $this->createForm(WorkType::class, $work);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $work->setUsers($this->getUser());
                $work->setActive(false);
                $work = $form->getData();
                if($this->getUser() != null) {
                    $this->entityManager->persist($work);
                    $evenement = new Evenement();
                    $evenement->setHiring($work);
                    $evenement->setContent('Ajouter');
                    $evenement->setUser($this->getUser());
                    $this->entityManager->persist($evenement);
                    $this->entityManager->flush();
                    $this->addFlash('message', 'Votre annonce a bien été créée.');
                    return $this->redirectToRoute('hiring');
                    } else {
                    
                        $this->addFlash('message', 'Veuillez vous connecter pour créer une offre.');
                        return $this->redirectToRoute('registration');
                    }

            }
        return $this->render('work/hiring.html.twig',[
            'form'=>$form->createView()
        ]);
    }

        /** 
    * @Route("/work/purpose", name= "purpose")
    */
    public function Purpose(Request $request): Response
    {
        
            $purpose = new Purpose();
            $form = $this->createForm(PurposeType::class, $purpose);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $purpose->setUsers($this->getUser());
                $purpose->setActive(false);
                $purpose = $form->getData();
    
                if($this->getUser() != null) {
                    $this->entityManager->persist($purpose);
                    $evenement = new Evenement();
                    $evenement->setUser($this->getUser());
                    $evenement->setPurpose($purpose);
                    $evenement->setContent("Ajouter");
                    $this->entityManager->persist($evenement);
                    $this->entityManager->flush();
                    $this->addFlash('message', 'Votre annonce a bien été créée.');
                    return $this->redirectToRoute('purpose');
                    } else {
                    
                        $this->addFlash('message', 'Veuillez vous connecter pour créer une offre.');
                        return $this->redirectToRoute('registration');
                    }
            }
        return $this->render('work/purpose.html.twig',[
            'form'=>$form->createView()

        ]);
    }

    /** 
    * @Route("/work/{slug}", name="work")
    */
    public function work($slug, WorkRepository $workRepository,Request $request): Response
    {
    
        $works = $workRepository->findOneBy(['slug' => $slug]);
        

        return $this->render('work/show.html.twig', [
            'works' => $works
        ]);
    }
    
        /** 
    * @Route("/purpose/{slug}", name="purposeshow")
    */
    public function purposeshow($slug, PurposeRepository $purposeRepository,Request $request): Response
    {
        $user = $request->getUser();
        $purposes = $purposeRepository->findOneBy(['slug' => $slug]);
        

        return $this->render('work/purposeshow.html.twig', [
            'purposes' => $purposes
        ]);
    }

}
