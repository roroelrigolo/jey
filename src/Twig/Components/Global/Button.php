<?php
namespace App\Twig\Components\Global;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Button
{
    public string $tag;
    public string $text;
    public string $route;
    public string $class = "";

    /**
     * @param string $class
     */
    public function setClass(string $class): void
    {
        $full_class = 'text-sm rounded-lg text-wrap px-4 py-2 mx-2';

        $classMappings = [
            "primary" => " bg-primary text-secondary hover:bg-secondary hover:text-primary",
            "primarylight" => " border border-primary text-primary hover:bg-secondary",
            "secondary" => " bg-secondary text-primary hover:bg-primary",
            "secondaryadmin" => " bg-secondary text-primary hover:bg-black hover:text-secondary",
            "secondarylight" => " border border-secondary text-secondary hover:bg-primary",
        ];

        $full_class .= isset($classMappings[$class]) ? $classMappings[$class] : '';
        $this->class = $full_class;
    }
}