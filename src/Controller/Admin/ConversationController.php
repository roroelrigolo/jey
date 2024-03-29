<?php

namespace App\Controller\Admin;

use App\Entity\Conversation;
use App\Form\Admin\ConversationFormType;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Repository\MessageStepRepository;
use App\Repository\NotificationRepository;
use Symfony\Component\Uid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/conversation')]
class ConversationController extends AbstractController
{
    #[Route('/', name: 'app_admin_conversation')]
    public function index(ConversationRepository $conversationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";
        
        $conversations = $conversationRepository->findBy([],[$filter=>$order]);
        $datas = [];

        foreach ($conversations as $conversation){
            $users = "";
            foreach ($conversation->getUsers() as $user){
                $users .= $user->getPseudo()." ";
            }
            $array = [
                $conversation->getId(),
                $conversation->getProduct()->getTitle(),
                $users,
                count($conversation->getMessages()),
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('admin/conversation/conversation.html.twig', [
            'datas' => $datas
        ]);
    }

    #[Route('/new', name: 'app_admin_conversation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ConversationRepository $conversationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $conversation = new Conversation();
        $form = $this->createForm(ConversationFormType::class, $conversation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conversation->setUuid(Uuid::uuid4()->toString());
            $conversation->setCreatedAt(new \DateTimeImmutable());
            $conversation->setUpdatedAt(new \DateTimeImmutable());
            $conversation->setRemove(1);
            $conversationRepository->add($conversation);
            $id = $conversation->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_conversation', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_conversation_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/conversation/new.html.twig', [
            'conversation' => $conversation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_conversation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ConversationRepository $conversationRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $conversation = $conversationRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(ConversationFormType::class, $conversation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conversation->setUpdatedAt(new \DateTimeImmutable());
            $conversationRepository->add($conversation);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_conversation', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_conversation_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/conversation/edit.html.twig', [
            'conversation' => $conversation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_conversation_delete', methods: ['POST'])]
    public function delete($id, Request $request, ConversationRepository $conversationRepository, MessageRepository $messageRepository,
                           NotificationRepository $notificationRepository, MessageStepRepository $messageStepRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $conversation = $conversationRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$conversation->getId(), $request->request->get('_token'))) {
            foreach ($conversation->getMessages() as $message){
                foreach ($message->getNotifications() as $notification){
                    $notificationRepository->remove($notification);
                }
                $messageRepository->remove($message);
            }
            foreach ($conversation->getMessageSteps() as $messageStep){
                $messageStepRepository->remove($messageStep);
            }
            $conversationRepository->remove($conversation);
        }
        return $this->redirectToRoute('app_admin_conversation', [], Response::HTTP_SEE_OTHER);
    }
}