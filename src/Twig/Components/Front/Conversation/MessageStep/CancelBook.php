<?php
namespace App\Twig\Components\Front\Conversation\MessageStep;

use App\Entity\MessageStep;
use App\Entity\Product;
use App\Repository\MessageStepRepository;
use App\Repository\ProductRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class CancelBook
{
    public string $message_step_id;
    public string $product_id;

    public function __construct(
        private MessageStepRepository $messageStepRepository,
        private ProductRepository $productRepository
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
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->productRepository->find($this->product_id);
    }
}