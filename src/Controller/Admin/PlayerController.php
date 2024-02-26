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

        for ($i=0;$i<count($players);$i++){
            $teams = "";
            foreach ($players[$i]->getTeams() as $team){
                $teams .= $team->getTitle().' ';
            }
            $available = ($players[$i]->isAvailable() == 1) ? "Oui" : "Non";
            $array = [
                $players[$i]->getId(),
                $players[$i]->getLastName(),
                $players[$i]->getFirstName(),
                $teams,
                $available,
                $players[$i]->getTemporaryName(),
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
            array_push($availables,$players[$i]->isAvailable());
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
            var_dump($_POST['teams']);
            /*
            $teams = [];
            foreach($_POST['teams'] as $item){
                array_push($teams,$item);
            }
            $player->setTeams($teams);
            */
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
            /*
            $teams = [];
            foreach($_POST['teams'] as $item){
                array_push($teams,$item);
            }
            $player->setTeams($teams);
            */
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
}