<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BackController extends AbstractController
{
    #[Route('/back/dashboard', name: 'app_back_dashboard')]
    public function back(): Response
    {
        return $this->render('back/back.html.twig', []);
    }

}