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
            return $this->playerRepository->findBy([],['lastName'=>'ASC']);
        }
        elseif ($this->options_name == 'teams'){
            return $this->teamRepository->findBy([],['title'=>'ASC']);
        }
        elseif ($this->options_name == 'leagues'){
            return $this->leagueRepository->findBy([],['title'=>'ASC']);
        }
        elseif ($this->options_name == 'colors'){
            return $this->colorRepository->findBy([],['title'=>'ASC']);
        }
        elseif ($this->options_name == 'departments'){
            return $this->departmentRepository->findBy([],['title'=>'ASC']);
        }
        return [];
    }

    public function getAllSports(): array
    {
        return $this->sportRepository->findBy([],['title'=>'ASC']);
    }

}