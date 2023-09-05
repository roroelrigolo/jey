<?php

namespace App\Controller\Front;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_front_home')]
    public function home(ProductRepository $productRepository): Response
    {
        return $this->render('front/home.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

}