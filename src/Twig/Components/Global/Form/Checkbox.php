<?php
namespace App\Twig\Components\Global\Form;

use App\Entity\Player;
use App\Entity\Team;
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

    public function __construct(
        private PlayerRepository $playerRepository,
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
}



