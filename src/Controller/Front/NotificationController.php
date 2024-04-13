<?php

namespace App\Controller\Front;

use App\Repository\NotificationRepository;
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

    #[Route('/{id}', name: 'app_front_notification_show', methods: ['GET', 'POST'])]
    public function show($id, NotificationRepository $notificationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $notification = $notificationRepository->find($id);
        $notification->setView(1);
        $notificationRepository->add($notification);

        $typeCategory = $notification->getType()->getCategory();

        if($typeCategory == "Signalements"){
            return $this->redirectToRoute('app_front_user_account_alert', [], Response::HTTP_SEE_OTHER);
        }
        elseif ($typeCategory == "Messages"){
            $uuidConversation = $notification->getMessage()->getConversation()->getUuid();
            return $this->redirectToRoute('app_front_conversation_show', ['uuid'=>$uuidConversation], Response::HTTP_SEE_OTHER);
        }
        elseif ($typeCategory == "Abonnements"){
            if($notification->getType()->getId() == 7){
                $pseudo = $notification->getSubscriber()->getPseudo();
                return $this->redirectToRoute('app_front_user_show', ['pseudo'=>$pseudo], Response::HTTP_SEE_OTHER);
            }
            else {
                $uuidProduct = $notification->getProduct()->getUuid();
                return $this->redirectToRoute('app_front_product_show', ['uuid'=>$uuidProduct], Response::HTTP_SEE_OTHER);
            }
        }
        else {
            return $this->redirectToRoute('app_front_home', [], Response::HTTP_SEE_OTHER);
        }
    }

}