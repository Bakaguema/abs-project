<?php

namespace App\Controller\Admin;

use App\Entity\FAQ;
use App\Form\FAQType;
use App\Repository\FAQRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class FAQController extends AbstractController
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
     * @Route("/admin/faq", name="admin_faq")
     */
    public function index(FAQRepository $FAQRepository): Response
    {
       
        return $this->render('admin/faq/index.html.twig', [
            'faqs' => $FAQRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/faq/ajout", name="add_faq")
     */
    public function add(Request $request): Response
    {
       
        $faq = new FAQ();
        $form = $this->createForm(FAQType::class, $faq);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $faq = $form->getData();

            $this->entityManager->persist($faq);
            $this->entityManager->flush();

            $this->addFlash('message', 'La nouvelle question / réponse a bien été ajouté.');

            return $this->redirectToRoute('admin_faq');
        }

        return $this->render('admin/faq/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/faq/editer/{id}", name="edit_faq")
     */
    public function edit(FAQ $faq,Request $request): Response
    {
       
        $form = $this->createForm(FAQType::class, $faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $faq = $form->getData();

            $this->entityManager->persist($faq);
            $this->entityManager->flush();

            $this->addFlash('message', 'La question / réponse à bien été mise à jour.');

            return $this->redirectToRoute('admin_faq');
        }

        return $this->render('admin/faq/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/faq/supprimer/{id}", name="delete_faq")
     */
    public function delete(FAQ $faq): Response
    {
       
        $this->entityManager->remove($faq);
        $this->entityManager->flush();

        $this->addFlash('message', "La question / réponse a bien été supprimé");

        return $this->redirectToRoute('admin_faq');
    }
}