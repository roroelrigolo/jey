<?php
namespace App\Twig\Components\Admin\Form;

use App\Entity\Player;
use App\Entity\Product;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Form
{
    public string $form_link;
    public \Symfony\Component\Form\FormView $form;
    public Product $product;
    public Player $player;
}