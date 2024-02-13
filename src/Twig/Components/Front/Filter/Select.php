<?php
namespace App\Twig\Components\Front\Filter;

use App\Enum;
use App\Repository\BrandRepository;
use App\Repository\PlayerRepository;
use App\Repository\SportRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Select
{

    public string $text;
    public string $variable;
    public string $options_name;
    public bool $object;

    public function __construct(
        private SportRepository $sportRepository,
        private BrandRepository $brandRepository,
        private PlayerRepository $playerRepository,
        private Enum $enum
    ) {
    }

    public function getOptions(): array
    {
        if($this->options_name == 'conditionnements'){
            return $this->enum->conditionnements;
        }
        elseif ($this->options_name == 'sizes'){
            return $this->enum->sizes;
        }
        elseif ($this->options_name == 'types'){
            return $this->enum->types;
        }
        elseif ($this->options_name == 'brands'){
            return $this->brandRepository->findBy([],['title'=>'ASC']);
        }
        elseif ($this->options_name == 'players'){
            return $this->playerRepository->findBy([],['lastName'=>'ASC']);
        }
        return [];
    }

    public function getAllSports(): array
    {
        return $this->sportRepository->findBy([],['title'=>'ASC']);
    }

}