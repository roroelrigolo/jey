<?php

namespace App\Controller\Front;

use App\Repository\ProductRepository;
use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_front_home')]
    public function home(): Response
    {
        return $this->render('front/home.html.twig', [

        ]);
    }

    #[Route('/search', name: 'app_search')]
    public function search(): Response
    {
        return $this->render('front/search.html.twig', [

        ]);
    }

}