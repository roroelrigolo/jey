<?php
namespace App\Twig\Components\Front\Nav;

use App\Repository\SportRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class NavMobile
{
    public array $items = [];

    public function __construct(
        private SportRepository $sportRepository
    ){
    }

    /**
     * @return array
     */
    public function getSports(): array
    {
        return $this->sportRepository->findBy(['displayMenu'=>1],['title'=>'ASC']);
    }
}