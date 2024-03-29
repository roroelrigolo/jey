<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Sport;
use App\Form\Admin\SportFormType;
use App\Repository\ImageRepository;
use App\Repository\LeagueRepository;
use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/sport')]
class SportController extends AbstractController
{
    #[Route('/', name: 'app_admin_sport')]
    public function index(SportRepository $sportRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";

        $sports = $sportRepository->findBy([],[$filter=>$order]);
        $datas = [];
        $availables = [];

        foreach ($sports as $sport){
            $displayMenu = ($sport->isDisplayMenu() == 1) ? "Oui" : "Non";
            $available = ($sport->isAvailable() == 1) ? "Oui" : "Non";
            $array = [
                $sport->getId(),
                $sport->getTitle(),
                $displayMenu,
                $available,
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
            array_push($availables,$sport->isAvailable());
        }

        return $this->render('admin/sport/sport.html.twig', [
            'datas' => $datas,
            'availables' => $availables
        ]);
    }

    #[Route('/new', name: 'app_admin_sport_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SportRepository $sportRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $sport = new Sport();
        $form = $this->createForm(SportFormType::class, $sport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sportRepository->add($sport);
            $id = $sport->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_sport', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_sport_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/sport/new.html.twig', [
            'sport' => $sport,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_sport_edit', methods: ['GET', 'POST'])]
    public function edit($id, Request $request, SportRepository $sportRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $sport = $sportRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(SportFormType::class, $sport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $sportRepository->add($sport);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_sport', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_sport_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/sport/edit.html.twig', [
            'sport' => $sport,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_sport_delete', methods: ['POST'])]
    public function delete(Request $request, SportRepository $sportRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $sport = $sportRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$sport->getId(), $request->request->get('_token'))) {
            $sportRepository->remove($sport);
        }
        return $this->redirectToRoute('app_admin_sport', [], Response::HTTP_SEE_OTHER);
    }
}