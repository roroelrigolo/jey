<?php
namespace App\Twig\Components\Global;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Button
{
    public string $tag;
    public string $text;
    public string $route;
    public array $route_parameter = [];
    public string $class = "";
    public string $size;

    /**
     * @param string $class
     */
    public function setClass(string $class): void
    {
        $full_class = 'rounded-lg text-nowrap h-fit px-4 py-2 me-2';

        $classMappings = [
            "primary" => " bg-primary text-secondary hover:bg-black",
            "primarylight" => " border border-primary text-primary hover:bg-secondary",
            "secondary" => " bg-secondary border border-secondary text-primary hover:bg-primary hover:bg-primary hover:text-secondary",
            "secondaryadmin" => " bg-secondary text-primary hover:bg-black hover:text-secondary",
            "secondarylight" => " border border-secondary text-secondary hover:bg-primary",
            "tertiary" => " bg-tertiary text-white hover:bg-primary",
        ];

        $full_class .= isset($classMappings[$class]) ? $classMappings[$class] : '';
        $this->class = $full_class;
    }
}