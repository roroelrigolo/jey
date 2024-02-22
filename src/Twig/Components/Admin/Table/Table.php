<?php
namespace App\Twig\Components\Admin;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Table
{
    public array $titles = [];
    public array $cols = [];
    public array $datas = [];
    public array $features = [];
    public array $types = [];
    public string $url;
}