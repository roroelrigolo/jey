<?php
namespace App\Twig\Components\Front\Footer;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class FooterCol
{
    public string $title;
    public array $items = [];
    public bool $social;
}