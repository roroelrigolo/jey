<?php
namespace App\Twig\Components\Admin\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Form
{
    public array $nav_items = [];
    public array $pages = [];
}