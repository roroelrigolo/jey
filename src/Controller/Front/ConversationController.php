<?php

namespace App\Controller\Front;

use App\Repository\ConversationRepository;;

use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/conversation')]
class ConversationController extends AbstractController
{
    #[Route('/', name: 'app_front_conversation')]
    public function home(ConversationRepository $conversationRepository, SportRepository $sportRepository): Response
    {
        $user = $this->getUser();
        return $this->render('front/conversation/conversation.html.twig', [
            'sports' => $sportRepository->findBy(['displayMenu'=>1],['title'=>'ASC']),
            'conversations' => $conversationRepository->findBy([],['updated_at'=>'DESC'])
        ]);
    }

}