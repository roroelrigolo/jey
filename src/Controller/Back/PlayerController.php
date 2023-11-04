<?php

namespace App\Controller\Back;

use App\Entity\Player;
use App\Entity\Sport;
use App\Form\PlayerFormType;
use App\Form\SportFormType;
use App\Repository\LeagueRepository;
use App\Repository\PlayerRepository;
use App\Repository\SportRepository;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/back/player')]
class PlayerController extends AbstractController
{
    #[Route('/', name: 'app_back_player')]
    public function index(PlayerRepository $playerRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $players = $playerRepository->findBy([],['id'=>'DESC']);
        $datas = [];

        for ($i=0;$i<count($players);$i++){
            $array = [
                $players[$i]->getId(),
                $players[$i]->getLastName(),
                $players[$i]->getFirstName(),
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('back/player/player.html.twig', [
            'datas' => $datas
        ]);
    }

    #[Route('/new', name: 'app_back_player_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlayerRepository $playerRepository, LeagueRepository $leagueRepository, TeamRepository $teamRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $player = new Player();
        $form = $this->createForm(PlayerFormType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teams = [];
            foreach($_POST['teams'] as $item){
                array_push($teams,$item);
            }
            $player->setTeams($teams);
            $playerRepository->add($player);
            $id = $player->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_back_player', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_back_player_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('back/player/new.html.twig', [
            'player' => $player,
            'leagues' => $leagueRepository->findAll(),
            'teams' => $teamRepository->findBy([],['title'=>'ASC']),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_back_player_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PlayerRepository $playerRepository,LeagueRepository $leagueRepository, TeamRepository $teamRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $player = $playerRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(PlayerFormType::class, $player);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teams = [];
            foreach($_POST['teams'] as $item){
                array_push($teams,$item);
            }
            $player->setTeams($teams);
            $playerRepository->add($player);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_back_player', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_back_player_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('back/player/edit.html.twig', [
            'player' => $player,
            'leagues' => $leagueRepository->findAll(),
            'teams' => $teamRepository->findBy([],['title'=>'ASC']),
            'form' => $form->createView(),
        ]);
    }
}