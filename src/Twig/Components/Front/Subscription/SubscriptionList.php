<?php
namespace App\Twig\Components\Front\Subscription;

use App\Entity\User;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class SubscriptionList
{
    public User $user;
    public string $type;

    public function getSubscriptions() {
        return $this->user->getSubscriptions();
    }

    public function getFollowers() {
        return $this->user->getFollowers();
    }
}