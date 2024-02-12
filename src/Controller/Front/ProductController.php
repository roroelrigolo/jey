<?php

namespace App\Controller\Front;

use App\Repository\ProductRepository;
use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/{id}', name: 'app_front_product_show')]
    public function show($id, ProductRepository $productRepository, SportRepository $sportRepository): Response
    {
        $product = $productRepository->findOneBy(['id'=>$id]);
        $sport = $product->getSport();

        return $this->render('front/product/show.html.twig', [
            'product' => $product,
            'productSimilary' => $productRepository->findBy(['sport'=>$sport],['created_at'=>'DESC']),
            'sports' => $sportRepository->findBy(['displayMenu'=>1],['title'=>'ASC'])
        ]);
    }

}