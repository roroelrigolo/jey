<?php
namespace App\Twig\Components\Front\Product;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class ProductListing
{

    public string $title;
    public string $listing;
    public int $user_id;

    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function getBestProducts(): array
    {
        return $this->productRepository->findBy([],['created_at'=>'DESC'], 5);
    }

    public function getLastProducts(): array
    {
        return $this->productRepository->findBy([],['created_at'=>'DESC'], 15);
    }

    public function getUserProducts(): array
    {
        return $this->productRepository->findBy(['user'=>$this->user_id],['created_at'=>'DESC']);
    }
}