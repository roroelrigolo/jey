<?php
namespace App\Twig\Components\Front\Subscription;

use App\Entity\Subscription;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class SubscriptionItem
{
    public Subscription $subscription;
    public string $type;
}