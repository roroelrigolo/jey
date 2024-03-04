<?php

namespace App\Controller\Admin;

use App\Entity\Alert;
use App\Form\Admin\AlertFormType;
use App\Repository\AlertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/alert')]
class AlertController extends AbstractController
{
    #[Route('/', name: 'app_admin_alert')]
    public function index(AlertRepository $alertRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";
        
        $alerts = $alertRepository->findBy([],[$filter=>$order]);
        $datas = [];

        foreach ($alerts as $alert){
            if($alert->getStatut() == "A vérifier"){
                $statut = '<span class="bg-red rounded px-2 text-secondary">'.$alert->getStatut().'</span>';
            }
            else {
                $statut = '<span class="bg-primary rounded px-2 text-secondary">'.$alert->getStatut().'</span>';
            }

            $array = [
                $alert->getId(),
                $alert->getDepositor()->getPseudo(),
                $alert->getType(),
                $statut,
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('admin/alert/alert.html.twig', [
            'datas' => $datas
        ]);
    }

    #[Route('/new', name: 'app_admin_alert_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AlertRepository $alertRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $alert = new Alert();
        $form = $this->createForm(AlertFormType::class, $alert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $alert->setCreatedAt(new \DateTimeImmutable());
            $alert->setUpdatedAt(new \DateTimeImmutable());
            $alertRepository->add($alert);
            $id = $alert->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_alert', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_alert_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/alert/new.html.twig', [
            'alert' => $alert,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_alert_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AlertRepository $alertRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $alert = $alertRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(AlertFormType::class, $alert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $alert->setUpdatedAt(new \DateTimeImmutable());
            $alertRepository->add($alert);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_alert', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_alert_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/alert/edit.html.twig', [
            'alert' => $alert,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_alert_delete', methods: ['POST'])]
    public function delete(Request $request, AlertRepository $alertRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $alert = $alertRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$alert->getId(), $request->request->get('_token'))) {
            $alertRepository->remove($alert);
        }
        return $this->redirectToRoute('app_admin_alert', [], Response::HTTP_SEE_OTHER);
    }
}