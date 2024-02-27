<?php
namespace App\Twig\Components\Global\Form;

use App\Entity\Player;
use App\Entity\Team;
use App\Repository\BrandRepository;
use App\Repository\ColorRepository;
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
    public string $description;
    public string $group_class;
    public string $typeName;
    public string $placeholder;
    public bool $required;
    public string $id_player = "";

    public function __construct(
        private TeamRepository $teamRepository,
        private PlayerRepository $playerRepository,
        private SportRepository $sportRepository,
        private LeagueRepository $leagueRepository,
        private BrandRepository $brandRepository,
        private ColorRepository $colorRepository,
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
        return $this->sportRepository->findBy(['available'=>1],['title'=>'ASC']);
    }

    /**
     * @return array
     */
    public function getLeagues(): array
    {
        return $this->leagueRepository->findBy(['available'=>1],['title'=>'ASC']);
    }

    /**
     * @return array
     */
    public function getTeams(): array {
        return $this->teamRepository->findBy(['available'=>1],['title'=>'ASC']);
    }

    /**
     * @return array
     */
    public function getPlayers(): array
    {
        return $this->playerRepository->findBy(['available'=>1],['lastName'=>'ASC']);
    }

    /**
     * @return array
     */
    public function getBrands(): array
    {
        return $this->brandRepository->findBy(['available'=>1],['title'=>'ASC']);
    }

    /**
     * @return array
     */
    public function getColors(): array
    {
        return $this->colorRepository->findBy([],['title'=>'ASC']);
    }
}



