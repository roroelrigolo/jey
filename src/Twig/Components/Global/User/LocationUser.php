<?php
namespace App\Twig\Components\Global\User;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class LocationUser
{
    public string $location;
}