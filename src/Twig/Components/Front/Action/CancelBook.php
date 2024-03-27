<?php
namespace App\Twig\Components\Front\Action;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class CancelBook
{
    public string $uuid;
    public string $class;
    public string $custom_class = "";

    public function __construct(
        private ProductRepository $productRepository
    ){
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->productRepository->findOneBy(['uuid'=>$this->uuid]);
    }
}