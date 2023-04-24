<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/abonnement", name="product")
     */
    public function index(ProductRepository $productRepository, Request $request): Response
    {           
        $products = $productRepository->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }
}
 