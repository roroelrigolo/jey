<?php

namespace App\Controller\Front;

use App\Entity\Message;
use App\Form\Front\MessageFormType;
use App\Repository\ConversationRepository;;

use App\Repository\MessageRepository;
use App\Repository\SportRepository;
use App\Repository\UserRepository;
use App\Service\NotificationService;
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
            return $this->redirectToRoute('app_front_conversation_show', ['uuid'=>$latestConversation->getUuid()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('front/conversation/conversation.html.twig', [
            'sports' => $sportRepository->findBy(['displayMenu'=>1],['title'=>'ASC']),
            'conversations' => $user->getConversations()
        ]);
    }

    #[Route('/{uuid}', name: 'app_front_conversation_show')]
    public function show(Request $request, MessageRepository $messageRepository, ConversationRepository $conversationRepository, SportRepository $sportRepository,
                         NotificationService $notificationService, $uuid): Response
    {
        $conversation = $conversationRepository->findOneBy(['uuid'=>$uuid]);
        $user = $this->getUser();

        $message = new Message();
        $form = $this->createForm(MessageFormType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setAuthor($this->getUser());
            $message->setConversation($conversation);
            $message->setCreatedAt(new \DateTimeImmutable());
            $message->setUpdatedAt(new \DateTimeImmutable());
            $messageRepository->add($message);

            $notificationService->addNotificationMessage($user, $message);

            $conversation->setUpdatedAt(new \DateTimeImmutable());
            $conversationRepository->add($conversation);

            return $this->redirectToRoute('app_front_conversation_show', ['uuid'=>$uuid], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/conversation/conversation.html.twig', [
            'sports' => $sportRepository->findBy(['displayMenu'=>1],['title'=>'ASC']),
            'conversations' => $user->getConversations(),
            'conversation_display' => $conversation,
            'form' => $form->createView()
        ]);
    }

}