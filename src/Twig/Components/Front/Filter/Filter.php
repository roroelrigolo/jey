<?php
namespace App\Twig\Components\Front\Filter;

use App\Entity\Sport;
use App\Entity\Team;
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
class Filter
{

    public Sport $sport;
    public string $custom_class;

    public function __construct(
        private SportRepository $sportRepository,
        private LeagueRepository $leagueRepository,
        private TeamRepository $teamRepository,
        private PlayerRepository $playerRepository,
        private BrandRepository $brandRepository,
        private ColorRepository $colorRepository,
        private TextilRepository $textilRepository,
        private DepartmentRepository $departmentRepository
    ) {
    }

    public function getAllSports(): array
    {
        return $this->sportRepository->findBy([],['title'=>'ASC']);
    }

    public function getFilters(): array
    {
        $filters = [];
        if ($_GET != []){
            foreach ($_GET as $key=>$items){
                if ($key == "sport"){
                    foreach ($items as $item){
                        $filter = $this->sportRepository->find($item);
                        array_push($filters, [$key,$filter->getId(),$filter->getTitle()]);
                    }
                }
                elseif ($key == "league"){
                    foreach ($items as $item){
                        $filter = $this->leagueRepository->find($item);
                        array_push($filters, [$key,$filter->getId(),$filter->getTitle()]);
                    }
                }
                elseif ($key == "team"){
                    foreach ($items as $item){
                        $filter = $this->teamRepository->find($item);
                        array_push($filters, [$key,$filter->getId(),$filter->getTitle()]);
                    }
                }
                elseif ($key == "player"){
                    foreach ($items as $item){
                        $filter = $this->playerRepository->find($item);
                        array_push($filters, [$key,$filter->getId(),$filter->getLastName().' '.$filter->getFirstName()]);
                    }
                }
                elseif ($key == "brand"){
                    foreach ($items as $item){
                        $filter = $this->brandRepository->find($item);
                        array_push($filters, [$key,$filter->getId(),$filter->getTitle()]);
                    }
                }
                elseif ($key == "colors"){
                    foreach ($items as $item){
                        $filter = $this->colorRepository->find($item);
                        array_push($filters, [$key,$filter->getId(),$filter->getTitle()]);
                    }
                }
                elseif ($key == "textils"){
                    foreach ($items as $item){
                        $filter = $this->textilRepository->find($item);
                        array_push($filters, [$key,$filter->getId(),$filter->getTitle()]);
                    }
                }
                elseif ($key == "departments"){
                    foreach ($items as $item){
                        $filter = $this->departmentRepository->find($item);
                        array_push($filters, [$key,$filter->getId(),$filter->getTitle()]);
                    }
                }
                elseif ($key == "text" or $key == "min-price" or $key == "max-price"){
                    if($items != ""){
                        array_push($filters, [$key,$items,$items]);
                    }
                }
                else {
                    foreach ($items as $item){
                        array_push($filters, [$key,$item,$item]);
                    }
                }
            }
        }
        return $filters;
    }

}