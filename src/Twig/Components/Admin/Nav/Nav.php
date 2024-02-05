<?php
namespace App\Twig\Components\Admin\Nav;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Nav
{
    public array $items = [];
}