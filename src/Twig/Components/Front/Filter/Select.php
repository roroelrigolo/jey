<?php
namespace App\Twig\Components\Front\Filter;

use App\Entity\Sport;
use App\Enum;
use App\Repository\BrandRepository;
use App\Repository\ColorRepository;
use App\Repository\DepartmentRepository;
use App\Repository\LeagueRepository;
use App\Repository\PlayerRepository;
use App\Repository\SportRepository;
use App\Repository\TeamRepository;
use App\Repository\TextilRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Select
{

    public string $text;
    public string $variable;
    public string $options_name;
    public bool $object;
    public bool $search;
    public Sport $sport;

    public function __construct(
        private SportRepository $sportRepository,
        private BrandRepository $brandRepository,
        private PlayerRepository $playerRepository,
        private TeamRepository $teamRepository,
        private LeagueRepository $leagueRepository,
        private ColorRepository $colorRepository,
        private DepartmentRepository $departmentRepository,
        private TextilRepository $textilRepository,
        private Enum $enum
    ) {
    }

    public function getOptions(): array
    {
        if($this->options_name == 'conditionnements'){
            return $this->enum::$conditionnements;
        }
        elseif ($this->options_name == 'sizes'){
            return $this->enum::$sizes;
        }
        elseif ($this->options_name == 'types'){
            return $this->enum::$types;
        }
        elseif ($this->options_name == 'numbers'){
            return $this->enum::$numbers;
        }
        elseif ($this->options_name == 'brands'){
            return $this->brandRepository->findBy([],['title'=>'ASC']);
        }
        elseif ($this->options_name == 'players'){
            if (isset($_GET['team'])){
                $id_teams = [];
                foreach ($_GET['team'] as $team){
                    array_push($id_teams, $team);
                }
                return $this->playerRepository->findByTeams($id_teams);
            }
            elseif (isset($this->sport)){
                return $this->playerRepository->findBySport($this->sport->getId());
            }
            else {
                return $this->playerRepository->findBy([],['lastName'=>'ASC']);
            }
        }
        elseif ($this->options_name == 'teams'){
            if (isset($_GET['league'])){
                $id_leagues = [];
                foreach ($_GET['league'] as $league){
                   array_push($id_leagues, $league);
                }
                return $this->teamRepository->findByLeagues($id_leagues);
            }
            elseif (isset($this->sport)){
                return $this->teamRepository->findBySport($this->sport->getId());
            }
            else {
                return $this->teamRepository->findBy([],['title'=>'ASC']);
            }
        }
        elseif ($this->options_name == 'leagues'){
            if (isset($_GET['sport'])){
                $id_sports = [];
                foreach ($_GET['sport'] as $sport){
                    array_push($id_sports, $sport);
                }
                return $this->leagueRepository->findBySport($id_sports);
            }
            elseif (isset($this->sport)){
                return $this->leagueRepository->findBySport($this->sport->getId());
            }
            else {
                return $this->leagueRepository->findBy([],['title'=>'ASC']);
            }
        }
        elseif ($this->options_name == 'colors'){
            return $this->colorRepository->findBy([],['title'=>'ASC']);
        }
        elseif ($this->options_name == 'textils'){
            return $this->textilRepository->findBy([],['title'=>'ASC']);
        }
        elseif ($this->options_name == 'departments'){
            return $this->departmentRepository->findBy([],['title'=>'ASC']);
        }
        elseif ($this->options_name == 'sports'){
            return $this->sportRepository->findBy([],['title'=>'ASC']);
        }
        return [];
    }

    public function getAllSports(): array
    {
        return $this->sportRepository->findBy([],['title'=>'ASC']);
    }

}