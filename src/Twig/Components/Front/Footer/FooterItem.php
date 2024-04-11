<?php
namespace App\Twig\Components\Front\Footer;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class FooterItem
{
    public array $item = [];
}