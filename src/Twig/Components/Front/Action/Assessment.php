<?php
namespace App\Twig\Components\Front\Action;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Assessment
{
    public string $uuid_product;
    public string $uuid_conversation;
    public string $class;
    public string $custom_class = "";
}