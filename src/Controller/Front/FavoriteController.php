<?php

namespace App\Controller\Front;

use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/favorite')]
class FavoriteController extends AbstractController
{
    #[Route('/', name: 'app_front_favorite')]
    public function home(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('front/favorite/favorite.html.twig', [

        ]);
    }

}