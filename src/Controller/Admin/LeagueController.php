<?php

namespace App\Controller\Admin;

use App\Entity\League;
use App\Form\Admin\LeagueFormType;
use App\Repository\LeagueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/league')]
class LeagueController extends AbstractController
{
    #[Route('/', name: 'app_admin_league')]
    public function index(LeagueRepository $leagueRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";

        $leagues = $leagueRepository->findBy([],[$filter=>$order]);
        $datas = [];
        $availables = [];

        for ($i=0;$i<count($leagues);$i++){
            $sports = "";
            foreach ($leagues[$i]->getSports() as $sport){
                $sports .= $sport->getTitle().' ';
            }
            $available = ($leagues[$i]->isAvailable() == 1) ? "Oui" : "Non";
            $array = [
                $leagues[$i]->getId(),
                $leagues[$i]->getTitle(),
                $sports,
                $available,
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
            array_push($availables,$leagues[$i]->isAvailable());
        }

        return $this->render('admin/league/league.html.twig', [
            'datas' => $datas,
            'availables' => $availables
        ]);
    }

    #[Route('/new', name: 'app_admin_league_new', methods: ['GET', 'POST'])]
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
                return $this->redirectToRoute('app_admin_league', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_league_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/league/new.html.twig', [
            'league' => $league,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_league_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LeagueRepository $leagueRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $league = $leagueRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(LeagueFormType::class, $league);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $leagueRepository->add($league);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_league', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_league_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/league/edit.html.twig', [
            'league' => $league,
            'form' => $form->createView(),
        ]);
    }
}