<?php
namespace App\Twig\Components\Global\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Page
{
    public string $id ;
    public string $class ;
    public array $inputs = [];
}


