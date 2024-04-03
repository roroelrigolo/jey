<?php

namespace App\Controller\Front;

use App\Entity\Conversation;
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
    public function home(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        if (count($user->getConversations()) != 0) {
            $latestConversation = null;
            foreach ($user->getConversations() as $conversation) {
                if (is_null($latestConversation) || $conversation->getUpdatedAt() > $latestConversation->getUpdatedAt()) {
                    $latestConversation = $conversation;
                }
            }
            return $this->redirectToRoute('app_front_conversation_show', ['uuid'=>$latestConversation->getUuid()], Response::HTTP_SEE_OTHER);
        }
        else {
            return $this->redirectToRoute('app_front_conversation_none', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/exit', name: 'app_front_conversation_exit')]
    public function exit(ConversationRepository $conversationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        if (!empty($user->getConversations())) {
            foreach ($user->getConversations() as $conversation) {
               if($conversation->isRemove() == 1){
                   $conversationRepository->remove($conversation);
               }
            }
        }
    }

    #[Route('/none', name: 'app_front_conversation_none')]
    public function none(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        if (count($user->getConversations()) != 0) {
            return $this->redirectToRoute('app_front_conversation', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('front/conversation/none.html.twig', [
        ]);
    }

    #[Route('/{uuid}', name: 'app_front_conversation_show')]
    public function show(Request $request, MessageRepository $messageRepository, ConversationRepository $conversationRepository,
                         NotificationService $notificationService, $uuid): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $conversation = $conversationRepository->findOneBy(['uuid'=>$uuid]);

        $messages = $conversation->getMessages();
        $messageSteps = $conversation->getMessageSteps();

        $alls_messages = [];
        foreach ($messages as $message){
            array_push($alls_messages, ['message',$message->getUpdatedAt(),$message]);
        }
        foreach ($messageSteps as $messageStep){
            array_push($alls_messages, ['messageStep',$messageStep->getUpdatedAt(),$messageStep]);
        }

        $message = new Message();
        $form = $this->createForm(MessageFormType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setAuthor($this->getUser());
            $message->setConversation($conversation);
            $message->setCreatedAt(new \DateTimeImmutable());
            $message->setUpdatedAt(new \DateTimeImmutable());
            $messageRepository->add($message);

            foreach ($conversation->getUsers() as $user){
                if($user->getId() != $this->getUser()->getId()){
                    $notificationService->addNotificationMessage($user, $message);
                }
            }

            $conversation->setUpdatedAt(new \DateTimeImmutable());
            $conversation->setRemove(0);
            $conversationRepository->add($conversation);

            return $this->redirectToRoute('app_front_conversation_show', ['uuid'=>$uuid], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/conversation/conversation.html.twig', [
            'conversations' => $this->getUser()->getConversations(),
            'alls_messages' => $alls_messages,
            'conversation_display' => $conversation,
            'form' => $form->createView()
        ]);
    }

}