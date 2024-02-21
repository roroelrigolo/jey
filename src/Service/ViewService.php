<?php
namespace App\Service;

use App\Entity\Product;
use App\Entity\ProductView;
use App\Repository\ProductViewRepository;

class ViewService
{

    public function setViewProduct($user, Product $product, ProductViewRepository $productViewRepository): string
    {
        if($user != null){
            //On regarde si la vue existe déjà et on l'update, sinon on l'ajoute
            $product_view = $productViewRepository->findOneBy(['user'=>$user,'product'=>$product],[]);
            if($product_view != null){
                $product_view->setUpdatedAt(new \DateTimeImmutable());
            }
            else {
                $product_view = new ProductView();
                $product_view->setUser($user);
                $product_view->setProduct($product);
                $product_view->setCreatedAt(new \DateTimeImmutable());
                $product_view->setUpdatedAt(new \DateTimeImmutable());
            }
            $productViewRepository->add($product_view);
        }
        return true;
    }
}