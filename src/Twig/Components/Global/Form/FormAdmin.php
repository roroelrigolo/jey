<?php
namespace App\Twig\Components\Global\Form;

use App\Entity\Player;
use App\Entity\Product;
use App\Repository\TeamRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class FormAdmin
{
    public string $form_link;
    public \Symfony\Component\Form\FormView $form;
    public Product $product;
    public Player $player;
}