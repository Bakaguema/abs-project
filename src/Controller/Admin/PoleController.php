<?php

namespace App\Controller\Admin;

use App\Entity\Pole;
use App\Form\PoleType;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\PoleRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PoleController extends AbstractController
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
     * @Route("/admin/pole", name="admin_pole")
     */
    public function index(PoleRepository $poleRepository): Response
    {
        $user = $this ->getUser();
        
        return $this->render('admin/pole/index.html.twig', [
            'user' => $user,
            'poles' => $poleRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/pole/ajout", name="add_pole")
     */
    public function add(Request $request): Response
    {
        $pole = new Pole();
        $form = $this->createForm(PoleType::class, $pole);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pole = $form->getData();

            $this->entityManager->persist($pole);
            $this->entityManager->flush();

            $this->addFlash('message', "Le nouveau pôle d'activité à bien été ajouté.");

            return $this->redirectToRoute('admin_pole');
        }

        return $this->render('admin/pole/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/pole/editer/{id}", name="edit_pole")
     */
    public function edit(Pole $pole,Request $request, UserRepository $userRepository): Response
    {
        $user = $this ->getUser();
        

        $form = $this->createForm(PoleType::class, $pole);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pole = $form->getData();
            $admin = $userRepository->findOneBy(["id" => $form->get("admin")->getData()]);
            $pole->setAdmin($admin);
            $this->entityManager->persist($pole);
            $this->entityManager->flush();

            $this->addFlash('message', "Le pôle d'activité à bien été mise à jour.");

            return $this->redirectToRoute('admin_pole');
        }

        return $this->render('admin/pole/edit.html.twig', [
            'pole' => $pole,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/pole/supprimer/{id}", name="delete_pole")
     */
    public function delete(Pole $pole): Response
    {
        $this->entityManager->remove($pole);
        $this->entityManager->flush();

        $this->addFlash('message', "Le pôle d'activité a bien été supprimé");

        return $this->redirectToRoute('admin_pole');
    }
}