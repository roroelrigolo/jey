<?php
namespace App\Twig\Components\Front\Conversation;

use App\Entity\Conversation;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class ConversationItem
{
    public Conversation $conversation;
    public Conversation $conversation_display;
}