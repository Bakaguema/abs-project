<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use Gedmo\Sluggable\Util\Urlizer;
use App\Repository\FriendRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProductController extends AbstractController
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
     * @Route("/admin/produit", name="admin_product")
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/produit/ajout", name="admin_add_product")
     */
    public function add(Request $request): Response
    {

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $uploadFile = $form['illustration']->getData();

            if ($uploadFile) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads/products';

                $originalFileName = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFileName).'-'.uniqid().'.'.$uploadFile->guessExtension();

                $uploadFile->move(
                    $destination,
                    $newFilename
                );

                $product->setIllustration($newFilename);
            }

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $this->addFlash('message', 'Votre produit a bien été créé.');

            return $this->redirectToRoute('admin_product');
        }

        return $this->render('admin/product/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/produit/editer/{id}", name="admin_edit_product")
     */
    public function edit(Product $product, Request $request): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $uploadFile = $form['illustration']->getData();

            if ($uploadFile) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads/products';

                $originalFileName = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFileName).'-'.uniqid().'.'.$uploadFile->guessExtension();

                $uploadFile->move(
                    $destination,
                    $newFilename
                );

                $product->setIllustration($newFilename);
            }

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            $this->addFlash('message', "Le produit a bien été mis à jour.");

            return $this->redirectToRoute('admin_product');
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/produit/supprimer/{id}", name="admin_delete_product")
     */
    public function delete(Product $product): Response
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();

        $this->addFlash('message', "Le produit a bien été supprimé");

        return $this->redirectToRoute('admin_product');
    }
}