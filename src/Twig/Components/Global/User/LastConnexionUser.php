<?php
namespace App\Twig\Components\Global\User;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class LastConnexionUser
{
    public \DateTime $last_connexion;
}