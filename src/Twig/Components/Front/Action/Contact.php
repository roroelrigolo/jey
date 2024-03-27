<?php
namespace App\Twig\Components\Front\Action;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Contact
{
    public string $uuid;
    public string $class;
    public string $custom_class = "";
}