<?php
namespace App\Twig\Components\Front\Conversation;

use App\Entity\Conversation;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class ConversationItemMobile
{
    public Conversation $conversation;
    public Conversation $conversation_display;
}