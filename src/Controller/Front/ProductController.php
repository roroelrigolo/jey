<?php

namespace App\Controller\Front;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/{id}', name: 'app_front_product_show')]
    public function show($id, ProductRepository $productRepository): Response
    {
        return $this->render('front/product/show.html.twig', [
            'product' => $productRepository->findOneBy(['id'=>$id])
        ]);
    }

}