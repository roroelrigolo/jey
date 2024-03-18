<?php
namespace App\Twig\Components\Front\Nav;

use App\Repository\SportRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class NavAccountItem
{
    public array $item = [];
}