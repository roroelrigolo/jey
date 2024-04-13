<?php

namespace App\Controller\Front;

use App\Entity\Assessment;
use App\Form\Front\AssessmentFormType;
use App\Repository\AssessmentRepository;
use App\Repository\ConversationRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/subscription')]
class SubscriptionController extends AbstractController
{
    #[Route('/{id_account}/new', name: 'app_front_subscription_new', methods: ['GET', 'POST'])]
    public function new(Request $request, $id_account,): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

    }

}