<?php
namespace App\Twig\Components\Global\Form;

use App\Entity\Player;
use App\Entity\Team;
use App\Repository\LeagueRepository;
use App\Repository\PlayerRepository;
use App\Repository\SportRepository;
use App\Repository\TeamRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Input
{
    public \Symfony\Component\Form\FormView $form;
    public string $input_name;
    public array $attributs = [];
    public string $type;
    public string $label;
    public string $typeName;
    public string $placeholder;
    public string $id_player = "";

    public function __construct(
        private TeamRepository $teamRepository,
        private PlayerRepository $playerRepository,
        private SportRepository $sportRepository,
        private LeagueRepository $leagueRepository,
    ){
    }

    /**
     * @param string $id_player
     */
    public function setIdPlayer(string $id_player): void
    {
        $this->id_player = $id_player;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player {
        $id_player = $this->id_player;
        return $this->playerRepository->find($id_player);
    }

    /**
     * @return array
     */
    public function getSports(): array
    {
        return $this->sportRepository->findBy([],['title'=>'ASC']);
    }

    /**
     * @return array
     */
    public function getLeagues(): array
    {
        return $this->leagueRepository->findBy([],['title'=>'ASC']);
    }

    /**
     * @return array
     */
    public function getTeams(): array {
        return $this->teamRepository->findBy([],['title'=>'ASC']);
    }
}



