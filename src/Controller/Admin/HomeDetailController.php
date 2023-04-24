<?php

namespace App\Controller\Admin;

use App\Entity\HomeDetail;
use App\Form\HomeDetailType;
use App\Repository\FriendRepository;
use App\Repository\HomeDetailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeDetailController extends AbstractController
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
     * @Route("/admin/home-detail", name="admin_home_detail")
     */
    public function index(HomeDetailRepository $homeDetailRepository): Response
    {

        return $this->render('admin/homeDetail/index.html.twig', [
            'homeDetails' => $homeDetailRepository->findAll()
        
        ]);
    }

    /**
     * @Route("/admin/home-detail/ajout", name="add_home_detail")
     */
    public function add(Request $request): Response
    {
        $homeDetail = new HomeDetail();
        $form = $this->createForm(HomeDetailType::class, $homeDetail);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $homeDetail = $form->getData();

            $this->entityManager->persist($homeDetail);
            $this->entityManager->flush();

            $this->addFlash('message', 'Les descriptions ont bien été ajouté.');

            return $this->redirectToRoute('add_home_detail');
        }

        return $this->render('admin/homeDetail/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/home-detail/editer/{id}", name="edit_home_detail")
     */
    public function edit(HomeDetail $homeDetail,Request $request): Response
    {
        $form = $this->createForm(HomeDetailType::class, $homeDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $homeDetail = $form->getData();

            $this->entityManager->persist($homeDetail);
            $this->entityManager->flush();

            $this->addFlash('message', 'Les descriptions ont bien été mise à jour.');

            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/homeDetail/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}