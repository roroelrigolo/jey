<?php
namespace App\Twig\Components\Front\Product;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class ProductComponent
{
    public int $id;
    public bool $scroll;

    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function getProduct(): Product
    {
        return $this->productRepository->find($this->id);
    }
}