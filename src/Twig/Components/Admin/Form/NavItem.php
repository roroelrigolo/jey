<?php
namespace App\Twig\Components\Admin\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class NavItem
{
    public string $id;
    public string $text;
    public string $class;
}


