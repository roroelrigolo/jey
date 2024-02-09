<?php
namespace App\Twig\Components\Admin\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Input
{
    public \Symfony\Component\Form\FormView $form;
    public string $input_name;
    public array $attributs = [];
    public string $type;
}



