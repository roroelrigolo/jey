<?php

namespace App\Controller\Front;

use App\Repository\ConversationRepository;;

use App\Repository\SportRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/conversation')]
class ConversationController extends AbstractController
{
    #[Route('/', name: 'app_front_conversation')]
    public function home(ConversationRepository $conversationRepository, SportRepository $sportRepository, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        if (!empty($user->getConversations())) {
            $latestConversation = null;
            foreach ($user->getConversations() as $conversation) {
                if (is_null($latestConversation) || $conversation->getUpdatedAt() > $latestConversation->getUpdatedAt()) {
                    $latestConversation = $conversation;
                }
            }
            return $this->redirectToRoute('app_front_conversation_show', ['uuid'=>$conversation->getUuid()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('front/conversation/conversation.html.twig', [
            'sports' => $sportRepository->findBy(['displayMenu'=>1],['title'=>'ASC']),
            'conversations' => $user->getConversations()
        ]);
    }

    #[Route('/{uuid}', name: 'app_front_conversation_show')]
    public function show(ConversationRepository $conversationRepository, SportRepository $sportRepository, $uuid): Response
    {
        $conversation = $conversationRepository->findOneBy(['uuid'=>$uuid]);
        $user = $this->getUser();
        return $this->render('front/conversation/show.html.twig', [
            'sports' => $sportRepository->findBy(['displayMenu'=>1],['title'=>'ASC']),
            'conversations' => $user->getConversations(),
            'conversation_display' => $conversation,
        ]);
    }

}