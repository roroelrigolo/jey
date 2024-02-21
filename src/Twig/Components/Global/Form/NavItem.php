<?php
namespace App\Twig\Components\Global\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class NavItem
{
    public string $id;
    public string $text;
    public string $class;
}


