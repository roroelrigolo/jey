<?php
namespace App\Service;

use App\Entity\Product;
use App\Entity\View;
use App\Repository\ViewRepository;

class ViewService
{

    public function setView($user, Product $product, ViewRepository $viewRepository): string
    {
        if($user != null){
            //On regarde si la vue existe déjà et on l'update, sinon on l'ajoute
            $view = $viewRepository->findOneBy(['user'=>$user,'product'=>$product],[]);
            if($view != null){
                $view->setUpdatedAt(new \DateTimeImmutable());
            }
            else {
                $view = new View();
                $view->setUser($user);
                $view->setProduct($product);
                $view->setCreatedAt(new \DateTimeImmutable());
                $view->setUpdatedAt(new \DateTimeImmutable());
            }
            $viewRepository->add($view);
        }
        return true;
    }
}