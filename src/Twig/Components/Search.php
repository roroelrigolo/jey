<?php
namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use App\Repository\PlayerRepository;

#[AsLiveComponent]
class Search
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';

    public function __construct(
        private PlayerRepository $playerRepository
    ) {
    }

    public function getPlayers(): array
    {
        return $this->playerRepository->findByQuery($this->query);
    }
}