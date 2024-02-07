<?php
namespace App\Twig\Components\Admin;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Header
{
    public array $buttons = [];
    public array $sort_filters = [];
}