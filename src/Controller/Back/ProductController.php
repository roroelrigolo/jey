<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/back/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_back_product')]
    public function index(): Response
    {
        return $this->render('back/product/product.html.twig', []);
    }

}