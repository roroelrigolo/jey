<?php
namespace App\Service;

use App\Entity\MessageStep;
use App\Repository\MessageStepRepository;

class MessageStepService
{

    public function __construct(
        private MessageStepRepository $messageStepRepository,
    )
    {
    }

    public function addMessageStep($type, $conversation) {
        $messageStep = new MessageStep();
        $messageStep->setConversation($conversation);
        $messageStep->setType($type);
        $messageStep->setCreatedAt(new \DateTimeImmutable());
        $messageStep->setUpdatedAt(new \DateTimeImmutable());
        $this->messageStepRepository->add($messageStep);
    }
}