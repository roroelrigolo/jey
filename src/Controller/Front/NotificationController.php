<?php

namespace App\Controller\Front;

use App\Repository\ProductRepository;
use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/notification')]
class NotificationController extends AbstractController
{
    #[Route('/', name: 'app_front_notification', methods: ['GET', 'POST'])]
    public function notification(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('front/notification/notification.html.twig', [
            'justMobile' => true
        ]);
    }

}