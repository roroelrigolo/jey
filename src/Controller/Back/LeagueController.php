<?php

namespace App\Controller\Back;

use App\Entity\League;
use App\Form\LeagueFormType;
use App\Repository\LeagueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/back/league')]
class LeagueController extends AbstractController
{
    #[Route('/', name: 'app_back_league')]
    public function index(LeagueRepository $leagueRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $leagues = $leagueRepository->findBy([],['id'=>'DESC']);
        $datas = [];

        for ($i=0;$i<count($leagues);$i++){
            $array = [
                $leagues[$i]->getId(),
                $leagues[$i]->getTitle(),
                $leagues[$i]->getSport()->getTitle(),
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('back/league/league.html.twig', [
            'datas' => $datas
        ]);
    }

    #[Route('/new', name: 'app_back_league_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LeagueRepository $leagueRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $league = new League();
        $form = $this->createForm(LeagueFormType::class, $league);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $leagueRepository->add($league);

            $id = $league->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_back_league', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_back_league_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('back/league/new.html.twig', [
            'league' => $league,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_league_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LeagueRepository $leagueRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $league = $leagueRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(LeagueFormType::class, $league);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $leagueRepository->add($league);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_back_league', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_back_league_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('back/league/edit.html.twig', [
            'league' => $league,
            'form' => $form->createView(),
        ]);
    }
}