<?php
namespace App\Twig\Components\Admin\Dashboard;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class DashboardItem
{
    public string $text;
    public string $route;
    public string $icon;
}