<?php
namespace App\Twig\Components\Front\Filter;

use App\Entity\Sport;
use App\Enum;
use App\Repository\SportRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Filter
{

    public Sport $sport;

    public function __construct(
        private SportRepository $sportRepository,
    ) {
    }

    public function getAllSports(): array
    {
        return $this->sportRepository->findBy([],['title'=>'ASC']);
    }

}