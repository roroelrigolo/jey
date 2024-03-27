<?php
namespace App\Twig\Components\Front\Action;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Book
{
    public string $uuid;
    public string $class;
    public string $custom_class = "";
}