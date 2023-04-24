<?php

namespace App\Controller\Admin;

use App\Entity\Partner;
use App\Form\PartnerType;
use App\Entity\Illustration;
use App\Repository\ArticleRepository;
use App\Repository\CodeRedeemRepository;
use App\Repository\EvenementRepository;
use App\Repository\ForumMessageRepository;
use Gedmo\Sluggable\Util\Urlizer;
use App\Repository\PartnerRepository;
use App\Repository\SignalementRepository;
use App\Repository\UserRepository;
use App\Repository\VisiteurRepository;
use App\Repository\VisiteurTempRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminController extends AbstractController
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
     * @Route("/admin", name="admin")
     */
    public function index(EvenementRepository $eventrepo, UserRepository $userrepository, SignalementRepository $signalementrepo, ArticleRepository $articlerepo, ForumMessageRepository $forummessage, CodeRedeemRepository $codeRedeem, VisiteurRepository $visiteurrepo): Response
    {
        $event = $eventrepo->findBy(array(),['create_at' => 'desc'], 2);
        $eventv2 = $eventrepo->findBy(array(),['edate' => 'desc'], 2);
        if ($event !=null && $eventv2 != null && $event == 2) {
            $tempsevent1 = $event[0]->getCreateAt();
            if($event[1]->getCreateAt() != null){
                $tempsevent2 = $event[1]->getCreateAt();
            }
            
            $etemps1 = $eventv2[0]->getEdate();
            if($eventv2[1]->getEdate() != null ){
                $etemps2 = $eventv2[1]->getEdate();
                if ($etemps2 > $tempsevent2) {
                    $event[1] = $eventv2[1];
                }
            }
            if ($etemps1 > $tempsevent1 ) {
                $event[0] = $eventv2[0];
            } elseif ($etemps1 > $tempsevent2) {
                $event[1] = $eventv2[0];
            }
        }

        $visiteur = $visiteurrepo->findAll();
        $visiteurtotal = 0;
        if ($visiteur != null) {
            foreach ($visiteur as $nbvisit ) {
                $visiteurtotal += $nbvisit->getNbvisit();
            }
        }

        $dateactuel = $visiteurrepo->findBy(array(),["date"=>"DESC"],24);

        if (count($dateactuel) == 24 ) {
            $datehier = $visiteurrepo->findBy(array(),["date"=>"DESC"],24,24);
        } else {
            $datehier = null;
        }
        
        return $this->render('admin/accueil.html.twig', [
            'coupon' => count($codeRedeem->findAll()),
            'message' => count($forummessage->findAll()),
            'article' => count($articlerepo->findAll()),
            'nbsignalement' => count($signalementrepo->findAll()),
            'nbuser' => count($userrepository->findAll()),
            'asignalement' => count($signalementrepo->findBy(["view" => false])),
            'count' => count($event),
            'visiteur' => $visiteurtotal,
            'dateactu' => $dateactuel,
            'datehier' => $datehier,
            'event' => $event
        ]);
    }
    
    /**
     * @Route("/admin/partenaires/add", name="admin_add_partner")
     */
    public function addpartner( Request $request): Response
    {

        $partner = new Partner();
            $form = $this->createForm(PartnerType::class, $partner);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $partner = $form->getData();
    
                $illustration = $form->get('illustration')->getData();
    
                if ($illustration) {
                    $destination = $this->getParameter('kernel.project_dir').'/public/uploads/partner';
                    
                    //$file->move($directory, $file->getClientOriginalName());
                    $originalFileName = pathinfo($illustration->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = Urlizer::urlize($originalFileName).'-'.uniqid().'.'.$illustration->guessExtension();
    
                    $illustration->move(
                        $destination,
                        $newFilename
                    );
                    $partner->setIllustration($newFilename);
                }
    
                $this->entityManager->persist($partner);
                $this->entityManager->flush();
    
    
                $this->addFlash('message', 'Votre partenaire a bien été créé.');
    
                return $this->redirectToRoute('admin');
            }
        return $this->render('admin/partner/add.html.twig',[
            'form'=>$form->createView(),
            'partner'=>$partner
        ]);
        }

    /**
     * @Route("/admin/partenaire", name="admin_partner")
     */
    public function article(PartnerRepository $partnerRepository): Response
    {

        $partners = $partnerRepository->findall(['name' => 'desc']);

        return $this->render('admin/partner/index.html.twig',[
            'partners'=>$partners
        ]);
    }

    /**
     * @Route("/admin/partenaires/editer/{id}", name="admin_edit_partner")
     */
    public function editArticle(Partner $partner,Request $request): Response
    {

        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partner = $form->getData();

            $illustrations = $form->get('illustration')->getData();

            foreach ($illustrations as $illustration) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads/partner';
                
                //$file->move($directory, $file->getClientOriginalName());
                $originalFileName = pathinfo($illustration->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFileName).'-'.uniqid().'.'.$illustration->guessExtension();

                $illustration->move(
                    $destination,
                    $newFilename
                );
                $partner->setIllustration($newFilename);
            }

            $this->entityManager->persist($partner);
            $this->entityManager->flush();

            $this->addFlash('message', 'Votre article a bien été mis à jour.');

            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/partner/edit.html.twig', [
            'partner' => $partner,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/supprime/partner/illustration/{id}", name="delete_partner_illustration")
     */
    public function deletePartnersIllustration(Illustration $illustration): Response
    {

        $this->entityManager->remove($illustration);
        $this->entityManager->flush();

        $this->addFlash('message', 'Votre image a bien été supprimée');
        return $this->redirectToRoute('admin_partner');
    }
}
