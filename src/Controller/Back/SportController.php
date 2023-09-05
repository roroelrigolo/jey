<?php

namespace App\Controller\Back;

use App\Entity\Sport;
use App\Form\SportFormType;
use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/back/sport')]
class SportController extends AbstractController
{
    #[Route('/', name: 'app_back_sport')]
    public function index(SportRepository $sportRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $sports = $sportRepository->findBy([],['id'=>'DESC']);
        $datas = [];

        for ($i=0;$i<count($sports);$i++){
            $array = [
                $sports[$i]->getId(),
                $sports[$i]->getTitle(),
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('back/sport/sport.html.twig', [
            'datas' => $datas
        ]);
    }

    #[Route('/new', name: 'app_back_sport_new', methods: ['GET', 'POST'])]
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
                return $this->redirectToRoute('app_back_sport', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_back_sport_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('back/sport/new.html.twig', [
            'sport' => $sport,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_sport_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SportRepository $sportRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $sport = $sportRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(SportFormType::class, $sport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $sportRepository->add($sport);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_back_sport', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_back_sport_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('back/sport/edit.html.twig', [
            'sport' => $sport,
            'form' => $form->createView(),
        ]);
    }
}