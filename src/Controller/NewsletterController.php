<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Form\NewsletterType;
use App\Form\UnsubscribeNewsletterType;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Writer\Csv as WriterCsv;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewsletterController extends AbstractController
{

    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em =$em;
    }
    
    /**
     * @Route("/newsletter", name="newsletter")
     */ 
    public function subNews(Request $request): Response
    {
       

        $user = $this->em->getRepository(User::class)->findOneBy(['id'=>$this->getUser()->getId()]);

        $subscribe = $this->getUser()->getIsSubscribe();
        $form = $this->createForm(NewsletterType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            
            $user->setIsSubscribe(true);
            $this->em->persist($user);
            $this->em->flush();
            
            
            $this->addFlash('success', 'Vous avez bien été inscrit à la newsletter');
            return $this->redirectToRoute('main');
       
        
        }
        return $this->render('newsletter/newsletter.html.twig', [
            'form' => $form->createView()
        ]);
        
    }
    
    //Récupération des informations des Users qui ont souscrit à la newsletter
    public function getData(): array
    {
        $user = $this->getUser();
        
        $list = [];
        $users = $this->em->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            if ($user->getIsSubscribe()) {
                $list[] = [
                    $user->getFirstname(),
                    $user->getLastname(),
                    $user->getEmail()
                ];
            }
        }
        return $list;
    }

    //Création d'un fichier.csv et enregistrement des informations des Users qui ont souscrit à la newsletter
    /**
     * @Route("/export", name="export")
     */ 
    public function export()
    {

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Liste des utilisateurs');

        $sheet->getCell('A1')->setValue('Prénom');
        $sheet->getCell('B1')->setValue('Nom');
        $sheet->getCell('C1')->setValue('Email');

        $sheet->fromArray($this->getData(), null, 'A2', true);


        $writer = new WriterCsv($spreadsheet);

        $writer->save('List_newsletter.csv');

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/unsubscribe/newsletter", name="unsubscribe_newsletter")
     */ 
    public function unsub(Request $request): Response
    {
       

        $user = $this->em->getRepository(User::class)->findOneBy(['id'=>$this->getUser()->getId()]);
        $subscribe = $this->getUser()->getIsSubscribe();
        $form = $this->createForm(UnsubscribeNewsletterType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            
            $user->setIsSubscribe(false);
            $this->em->persist($user);
            $this->em->flush();
            
            
            $this->addFlash('succes', 'Vous avez bien été desinscrit de la newsletter');
            return $this->redirectToRoute('main');
       
        
        }
        return $this->render('unsubscribe_newsletter/unsubscribeNewsletter.html.twig', [
            'form' => $form->createView()


        ]);
        
        
    }
}