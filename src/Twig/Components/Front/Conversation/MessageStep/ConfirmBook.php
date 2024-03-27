<?php
namespace App\Twig\Components\Front\Conversation\MessageStep;

use App\Entity\Conversation;
use App\Entity\MessageStep;
use App\Entity\Product;
use App\Repository\AssessmentRepository;
use App\Repository\ConversationRepository;
use App\Repository\MessageStepRepository;
use App\Repository\ProductRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class ConfirmBook
{
    public string $message_step_id;
    public string $product_id;
    public string $conversation_id;
    public string $user_id;
    public bool $assessment;

    public function __construct(
        private MessageStepRepository $messageStepRepository,
        private AssessmentRepository $assessmentRepository,
        private ProductRepository $productRepository,
        private ConversationRepository $conversationRepository
    ){
    }

    /**
     * @return MessageStep
     */
    public function getMessageStep(): MessageStep
    {
        return $this->messageStepRepository->find($this->message_step_id);
    }

    /**
     * @return bool
     */
    public function isAssessment(): bool
    {
        if($this->assessmentRepository->findOneBy(['depositor'=>$this->user_id, 'product'=>$this->product_id])){
            $this->assessment = true;
        }
        else {
            $this->assessment = false;
        }
        return $this->assessment;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->productRepository->find($this->product_id);
    }

    /**
     * @return Conversation
     */
    public function getConversation(): Conversation
    {
        return $this->conversationRepository->find($this->conversation_id);
    }
}