<?php

namespace App\Controller\Admin;

use App\Entity\Subscription;
use App\Form\Admin\SubscriptionFormType;
use App\Repository\SubscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/subscription')]
class SubscriptionController extends AbstractController
{
    #[Route('/', name: 'app_admin_subscription')]
    public function index(SubscriptionRepository $subscriptionRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";

        $subscriptions = $subscriptionRepository->findBy([],[$filter=>$order]);
        $datas = [];

        foreach ($subscriptions as $subscription){
            $array = [
                $subscription->getId(),
                $subscription->getSubscriber()->getPseudo(),
                $subscription->getAccount()->getPseudo(),
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('admin/subscription/subscription.html.twig', [
            'datas' => $datas
        ]);
    }

    #[Route('/new', name: 'app_admin_subscription_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SubscriptionRepository $subscriptionRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $subscription = new Subscription();
        $form = $this->createForm(SubscriptionFormType::class, $subscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subscription->setCreatedAt(new \DateTimeImmutable());
            $subscription->setUpdatedAt(new \DateTimeImmutable());
            $subscriptionRepository->add($subscription);
            $id = $subscription->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_subscription', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_subscription_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/subscription/new.html.twig', [
            'subscription' => $subscription,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_subscription_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SubscriptionRepository $subscriptionRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $subscription = $subscriptionRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(SubscriptionFormType::class, $subscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subscription->setUpdatedAt(new \DateTimeImmutable());
            $subscriptionRepository->add($subscription);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_subscription', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_subscription_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/subscription/edit.html.twig', [
            'subscription' => $subscription,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_subscription_delete', methods: ['POST'])]
    public function delete(Request $request, SubscriptionRepository $subscriptionRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $subscription = $subscriptionRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$subscription->getId(), $request->request->get('_token'))) {
            $subscriptionRepository->remove($subscription);
        }
        return $this->redirectToRoute('app_admin_subscription', [], Response::HTTP_SEE_OTHER);
    }
}