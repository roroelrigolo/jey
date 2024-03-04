<?php

namespace App\Controller\Admin;

use App\Entity\Team;
use App\Form\Admin\TeamFormType;
use App\Repository\LeagueRepository;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/team')]
class TeamController extends AbstractController
{
    #[Route('/', name: 'app_admin_team')]
    public function index(TeamRepository $teamRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";

        $teams = $teamRepository->findBy([],[$filter=>$order]);
        $datas = [];
        $availables = [];

        foreach ($teams as $team){
            $leagues = "";
            $sports = "";
            foreach ($team->getLeagues() as $league){
                $leagues .= $league->getTitle().' ';
                foreach ($league->getSports() as $sport){
                    $sports .= $sport->getTitle().' ';
                }
            }
            $available = ($team->isAvailable() == 1) ? "Oui" : "Non";
            $array = [
                $team->getId(),
                $team->getTitle(),
                $sports,
                $leagues,
                $available,
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
            array_push($availables,$team->isAvailable());
        }

        return $this->render('admin/team/team.html.twig', [
            'datas' => $datas,
            'availables' => $availables
        ]);
    }

    #[Route('/new', name: 'app_admin_team_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TeamRepository $teamRepository, LeagueRepository $leagueRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $team = new Team();
        $form = $this->createForm(TeamFormType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teamRepository->add($team);

            $id = $team->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_team', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_team_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/team/new.html.twig', [
            'team' => $team,
            'leagues' => $leagueRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_team_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TeamRepository $teamRepository, LeagueRepository $leagueRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $team = $teamRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(TeamFormType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $teamRepository->add($team);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_team', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_team_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/team/edit.html.twig', [
            'team' => $team,
            'leagues' => $leagueRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_team_delete', methods: ['POST'])]
    public function delete(Request $request, TeamRepository $teamRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $team = $teamRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$team->getId(), $request->request->get('_token'))) {
            $teamRepository->remove($team);
        }
        return $this->redirectToRoute('app_admin_team', [], Response::HTTP_SEE_OTHER);
    }
}