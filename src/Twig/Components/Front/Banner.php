<?php
namespace App\Twig\Components\Front;

use App\Repository\SportRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Banner
{
    public string $title;
}