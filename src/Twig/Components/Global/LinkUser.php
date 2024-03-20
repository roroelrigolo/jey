<?php
namespace App\Twig\Components\Global;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class LinkUser
{
    public string $pseudo;
}