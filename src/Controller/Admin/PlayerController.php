<?php

namespace App\Controller\Admin;

use App\Entity\Player;
use App\Entity\Sport;
use App\Form\Admin\PlayerFormType;
use App\Form\Admin\SportFormType;
use App\Repository\LeagueRepository;
use App\Repository\PlayerRepository;
use App\Repository\SportRepository;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/player')]
class PlayerController extends AbstractController
{
    #[Route('/', name: 'app_admin_player')]
    public function index(PlayerRepository $playerRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";

        $players = $playerRepository->findBy([],[$filter=>$order]);
        $datas = [];
        $availables = [];

        foreach ($players as $player){
            $teams = "";
            foreach ($player->getTeams() as $team){
                $teams .= $team->getTitle().' ';
            }
            $available = ($player->isAvailable() == 1) ? "Oui" : "Non";
            $array = [
                $player->getId(),
                $player->getLastName(),
                $player->getFirstName(),
                $teams,
                $available,
                $player->getTemporaryName(),
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
            array_push($availables,$player->isAvailable());
        }

        return $this->render('admin/player/player.html.twig', [
            'datas' => $datas,
            'availables' => $availables
        ]);
    }

    #[Route('/new', name: 'app_admin_player_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlayerRepository $playerRepository, LeagueRepository $leagueRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $player = new Player();
        $form = $this->createForm(PlayerFormType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playerRepository->add($player);
            $id = $player->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_player', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_player_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/player/new.html.twig', [
            'player' => $player,
            'leagues' => $leagueRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_player_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PlayerRepository $playerRepository,LeagueRepository $leagueRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $player = $playerRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(PlayerFormType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $playerRepository->add($player);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_player', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_player_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/player/edit.html.twig', [
            'player' => $player,
            'leagues' => $leagueRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_player_delete', methods: ['POST'])]
    public function delete(Request $request, PlayerRepository $playerRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $player = $playerRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$player->getId(), $request->request->get('_token'))) {
            $playerRepository->remove($player);
        }
        return $this->redirectToRoute('app_admin_player', [], Response::HTTP_SEE_OTHER);
    }
}