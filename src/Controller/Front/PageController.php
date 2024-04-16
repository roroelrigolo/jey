<?php

namespace App\Controller\Front;

use App\Repository\ProductRepository;
use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/presentation', name: 'app_front_page_presentation')]
    public function presentation(): Response
    {
        return $this->render('front/page/presentation.html.twig', [

        ]);
    }

    #[Route('/advice', name: 'app_front_page_advice')]
    public function advice(): Response
    {
        return $this->render('front/page/advice.html.twig', [

        ]);
    }

    #[Route('/faq', name: 'app_front_page_faq')]
    public function faq(): Response
    {
        return $this->render('front/page/faq.html.twig', [

        ]);
    }

}