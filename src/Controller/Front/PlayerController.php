<?php

namespace App\Controller\Front;

use App\Repository\ProductRepository;
use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/player')]
class PlayerController extends AbstractController
{
    #[Route('/new', name: 'app_front_player_add')]
    public function add(): Response
    {
        return $this->render('front/player/new.html.twig', [
        ]);
    }

}