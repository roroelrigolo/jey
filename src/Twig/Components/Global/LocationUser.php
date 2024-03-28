<?php
namespace App\Twig\Components\Global;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class LocationUser
{
    public string $location;
}