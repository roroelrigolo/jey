<?php
namespace App\Twig\Components\Global\Form;

use App\Entity\Conversation;
use App\Entity\League;
use App\Entity\Player;
use App\Entity\Product;
use App\Entity\Team;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class FormAdmin
{
    public string $form_link;
    public \Symfony\Component\Form\FormView $form;
    public Product $product;
    public Player $player;
    public Team $team;
    public League $league;
    public Conversation $conversation;
}