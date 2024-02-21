<?php
namespace App\Twig\Components\Front\Product;

use App\Entity\ProductLike;
use App\Repository\ProductLikeRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
class ProductLikeComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public int $user_id;

    #[LiveProp(writable: true)]
    public int $product_id;

    public bool $isLike;

    public function __construct(
        private ProductLikeRepository $productLikeRepository,
        private ProductRepository $productRepository,
        private UserRepository $userRepository
    ) {
    }


    /**
     * @return bool
     */
    public function isLike(): bool
    {
        $product_like = $this->productLikeRepository->findOneBy(['user'=>$this->user_id,'product'=>$this->product_id]);
        if($product_like != null){
            $this->isLike = true;
        }
        else {
            $this->isLike = false;
        }
        return $this->isLike;
    }

    #[LiveAction]
    public function add()
    {
        $product_like = new ProductLike();
        $product_like->setUser($this->userRepository->find($this->user_id));
        $product_like->setProduct($this->productRepository->find($this->product_id));
        $product_like->setCreatedAt(new \DateTimeImmutable());
        $this->productLikeRepository->add($product_like);
        $this->isLike = true;
    }

    #[LiveAction]
    public function delete()
    {
        $product_like = $this->productLikeRepository->findOneBy(['user'=>$this->user_id,'product'=>$this->product_id]);
        if($product_like != null){
            $this->productLikeRepository->remove($product_like);
        }
        $this->isLike = false;
    }

    public function getCountLikes(): int
    {
        $count = count($this->productLikeRepository->findBy(['product'=>$this->product_id]));
        return $count;
    }
}