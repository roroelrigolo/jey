<?php
namespace App\Twig\Components\Global\User;

use App\Entity\User;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class FollowersUser
{
    public User $user;
}