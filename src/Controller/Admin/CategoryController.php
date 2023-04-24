<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CategoryController extends AbstractController
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
     * @Route("/admin/categorie", name="category")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
       
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/categorie/ajout", name="add_category")
     */
    public function add(Request $request): Response
    {
       
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $this->addFlash('message', 'La nouvelle catégorie à bien été ajouté.');

            return $this->redirectToRoute('category');
        }

        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/categorie/editer/{id}", name="edit_category")
     */
    public function edit(Category $category,Request $request): Response
    {
       
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $this->addFlash('message', 'La catégorie a bien été mise à jour.');

            return $this->redirectToRoute('category');
        }

        return $this->render('admin/category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/categorie/supprimer/{id}", name="delete_category")
     */
    public function delete(Category $category): Response
    {
       
        $this->entityManager->remove($category);
        $this->entityManager->flush();

        $this->addFlash('message', "La catégorie a bien été supprimé");

        return $this->redirectToRoute('category');
    }
}