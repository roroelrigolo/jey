<?php
namespace App\Twig\Components\Admin\Nav;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class NavItems
{
    public string $text;
    public string $route;
    public string $icon;
    public string $url;
}