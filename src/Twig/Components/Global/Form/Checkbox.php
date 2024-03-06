<?php
namespace App\Twig\Components\Global\Form;

use App\Entity\Conversation;
use App\Entity\League;
use App\Entity\Player;
use App\Entity\Team;
use App\Repository\ConversationRepository;
use App\Repository\LeagueRepository;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Checkbox
{
    public \Symfony\Component\Form\FormView $form;
    public string $input_name;
    public array $attributs = [];
    public string $id_player = "";
    public string $id_team = "";
    public string $id_league = "";
    public string $id_conversation = "";

    public function __construct(
        private PlayerRepository $playerRepository,
        private TeamRepository $teamRepository,
        private LeagueRepository $leagueRepository,
        private ConversationRepository $conversationRepository
    ){
    }

    /**
     * @param string $id_player
     */
    public function setIdPlayer(string $id_player): void
    {
        $this->id_player = $id_player;
    }

    public function getPlayer(): Player {
        $id_player = $this->id_player;
        return $this->playerRepository->find($id_player);
    }

    /**
     * @param string $id_team
     */
    public function setIdTeam(string $id_team): void
    {
        $this->id_team = $id_team;
    }

    public function getTeam(): Team {
        $id_team = $this->id_team;
        return $this->teamRepository->find($id_team);
    }

    /**
     * @param string $id_league
     */
    public function setIdLeague(string $id_league): void
    {
        $this->id_league = $id_league;
    }

    public function getLeague(): League {
        $id_league = $this->id_league;
        return $this->leagueRepository->find($id_league);
    }

    /**
     * @param string $id_conversation
     */
    public function setIdConversation(string $id_conversation): void
    {
        $this->id_conversation = $id_conversation;
    }

    public function getConversation(): Conversation {
        $id_conversation = $this->id_conversation;
        return $this->conversationRepository->find($id_conversation);
    }
}



