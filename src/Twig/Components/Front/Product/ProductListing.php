<?php
namespace App\Twig\Components\Front\Product;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\ProductViewRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class ProductListing
{

    public string $title;
    public string $listing;
    public int $user_id;
    public int $sport_id;

    public function __construct(
        private ProductRepository $productRepository,
    ) {
    }

    public function getBestProducts(): array
    {
        //On récupère les produits les plus populaires en fonction d'un ratio de popularité 80% favoris/20% vues
        $products = $this->productRepository->findAll();
        $array_product_ratio = array();
        foreach ($products as $product){
            $popular_ratio = (count($product->getProductViews())*0.2+count($product->getProductLikes())*0.8);
            array_push($array_product_ratio, [$popular_ratio,$product->getId()]);
        }
        rsort($array_product_ratio);
        //On garde les 5 premiers produits les plus populaires
        $array_product_ratio = array_slice($array_product_ratio, 0, 5);
        $products = array();
        foreach ($array_product_ratio as $product){
            array_push($products, $this->productRepository->find($product[1]));
        }
        return $products;
    }

    public function getLastProducts(): array
    {
        return $this->productRepository->findBy([],['created_at'=>'DESC'], 15);
    }

    public function getUserProducts(): array
    {
        return $this->productRepository->findBy(['user'=>$this->user_id],['created_at'=>'DESC']);
    }

    public function getSportProducts(): array
    {
        if($_GET != null){
            unset($_GET["query"]);
            $colors = $_GET["colors"];
            unset($_GET["colors"]);
            $players = $this->productRepository->findBy($_GET,['created_at'=>'DESC']);
            //On flitre avec les couleurs si définies
            if ($colors !== null) {
                $filteredPlayers = [];
                foreach ($players as $player) {
                    $colors_player = $player->getColors();
                    foreach ($colors_player as $color_player) {
                        if (in_array($color_player->getId(), $colors)) {
                            $filteredPlayers[] = $player;
                            break;
                        }
                    }
                }
                $players = $filteredPlayers;
            }
            return $players;
        }
        else{
            return $this->productRepository->findBy(['sport'=>$this->sport_id],['created_at'=>'DESC']);
        }
    }
}