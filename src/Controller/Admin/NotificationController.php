<?php

namespace App\Controller\Admin;

use App\Entity\Notification;
use App\Entity\NotificationType;
use App\Form\Admin\NotificationFormType;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/notification')]
class NotificationController extends AbstractController
{
    #[Route('/', name: 'app_admin_notification')]
    public function index(NotificationRepository $notificationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";
        
        $notifications = $notificationRepository->findBy([],[$filter=>$order]);
        $datas = [];

        foreach ($notifications as $notification){
            $view = ($notification->isView() == 1) ? "Oui" : "Non";
            $array = [
                $notification->getId(),
                $notification->getType()->getTitle(),
                $notification->getUser()->getPseudo(),
                $view,
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('admin/notification/notification.html.twig', [
            'datas' => $datas
        ]);
    }

    #[Route('/new', name: 'app_admin_notification_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NotificationRepository $notificationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $notification = new Notification();
        $form = $this->createForm(NotificationFormType::class, $notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification->setCreatedAt(new \DateTimeImmutable());
            $notification->setUpdatedAt(new \DateTimeImmutable());
            $notificationRepository->add($notification);
            $id = $notification->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_notification', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_notification_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/notification/new.html.twig', [
            'notification' => $notification,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_notification_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NotificationRepository $notificationRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $notification = $notificationRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(NotificationFormType::class, $notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification->setUpdatedAt(new \DateTimeImmutable());
            $notificationRepository->add($notification);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_notification', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_notification_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/notification/edit.html.twig', [
            'notification' => $notification,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_notification_delete', methods: ['POST'])]
    public function delete(Request $request, NotificationRepository $notificationRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $notification = $notificationRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$notification->getId(), $request->request->get('_token'))) {
            $notificationRepository->remove($notification);
        }
        return $this->redirectToRoute('app_admin_notification', [], Response::HTTP_SEE_OTHER);
    }
}