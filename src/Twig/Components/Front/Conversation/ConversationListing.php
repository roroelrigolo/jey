<?php
namespace App\Twig\Components\Front\Conversation;

use App\Entity\Conversation;
use App\Entity\User;
use App\Repository\ConversationRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class ConversationListing
{
    public User $user;
    public Conversation $conversation_display;

    public function getConversations()
    {
        return $this->user->getConversations();
    }
}