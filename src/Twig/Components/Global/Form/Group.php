<?php
namespace App\Twig\Components\Global\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Group
{
    public string $id;
    public string $class;
    public string $title;
    public string $custom;
    public array $inputs = [];
}


