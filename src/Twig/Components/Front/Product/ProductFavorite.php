<?php
namespace App\Twig\Components\Front\Product;

use App\Entity\Favorite;
use App\Repository\FavoriteRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
class ProductFavorite
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public int $user_id;

    #[LiveProp(writable: true)]
    public int $product_id;

    public bool $isFav;

    public function __construct(
        private FavoriteRepository $favoriteRepository,
        private ProductRepository $productRepository,
        private UserRepository $userRepository
    ) {
    }


    /**
     * @return bool
     */
    public function isFav(): bool
    {
        $favorite = $this->favoriteRepository->findOneBy(['user'=>$this->user_id,'product'=>$this->product_id]);
        if($favorite != null){
            $this->isFav = true;
        }
        else {
            $this->isFav = false;
        }
        return $this->isFav;
    }

    #[LiveAction]
    public function add()
    {
        $favorite = new Favorite();
        $favorite->setUser($this->userRepository->find($this->user_id));
        $favorite->setProduct($this->productRepository->find($this->product_id));
        $favorite->setCreatedAt(new \DateTimeImmutable());
        $this->favoriteRepository->add($favorite);
        $this->isFav = true;
    }

    #[LiveAction]
    public function delete()
    {
        $favorite = $this->favoriteRepository->findOneBy(['user'=>$this->user_id,'product'=>$this->product_id]);
        if($favorite != null){
            $this->favoriteRepository->remove($favorite);
        }
        $this->isFav = false;
    }

    public function getCountFavs(): int
    {
        $count = count($this->favoriteRepository->findBy(['product'=>$this->product_id]));
        return $count;
    }
}