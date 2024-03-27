<?php
namespace App\Twig\Components\Front\Action;

use App\Entity\Sport;
use App\Enum;
use App\Repository\SportRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Assessment
{
    public string $uuid;
    public string $class;
    public string $custom_class = "";
}