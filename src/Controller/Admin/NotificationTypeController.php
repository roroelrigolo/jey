<?php

namespace App\Controller\Admin;

use App\Entity\NotificationType;
use App\Form\Admin\NotificationTypeFormType;
use App\Repository\NotificationRepository;
use App\Repository\NotificationTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/notification_type')]
class NotificationTypeController extends AbstractController
{
    #[Route('/', name: 'app_admin_notification_type')]
    public function index(NotificationTypeRepository $notificationTypeRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";
        
        $notifications_type = $notificationTypeRepository->findBy([],[$filter=>$order]);
        $datas = [];

        foreach ($notifications_type as $notification_type){
            $array = [
                $notification_type->getId(),
                $notification_type->getType(),
                $notification_type->getCategory(),
                $notification_type->getTitle(),
                $notification_type->getContent(),
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('admin/notification_type/notification_type.html.twig', [
            'datas' => $datas
        ]);
    }

    #[Route('/new', name: 'app_admin_notification_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NotificationTypeRepository $notificationTypeRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $notification_type = new NotificationType();
        $form = $this->createForm(NotificationTypeFormType::class, $notification_type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notificationTypeRepository->add($notification_type);
            $id = $notification_type->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_notification_type', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_notification_type_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/notification_type/new.html.twig', [
            'notification_type' => $notification_type,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_notification_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NotificationTypeRepository $notificationTypeRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $notification_type = $notificationTypeRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(NotificationTypeFormType::class, $notification_type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notificationTypeRepository->add($notification_type);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_notification_type', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_notification_type_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/notification_type/edit.html.twig', [
            'notification_type' => $notification_type,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_notification_type_delete', methods: ['POST'])]
    public function delete(Request $request, NotificationTypeRepository $notificationTypeRepository, NotificationRepository $notificationRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $notification_type = $notificationTypeRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$notification_type->getId(), $request->request->get('_token'))) {
            foreach($notification_type->getNotifications() as $notification){
                $notificationRepository->remove($notification);
            }
            $notificationTypeRepository->remove($notification_type);
        }
        return $this->redirectToRoute('app_admin_notification_type', [], Response::HTTP_SEE_OTHER);
    }
}