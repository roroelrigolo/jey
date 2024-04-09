<?php
namespace App\Twig\Components\Front\Breadcrumb;

use App\Repository\SportRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Breadcrumb
{
    public array $items = [];
}