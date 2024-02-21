<?php
namespace App\Twig\Components\Global\Form;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class FormFront
{
    public string $form_link;
    public array $sommaire = [];
    public \Symfony\Component\Form\FormView $form;
}