<?php

namespace App\Controller\Front;

use App\Repository\ProductRepository;
use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sport')]
class SportController extends AbstractController
{
    #[Route('/{slug}', name: 'app_front_sport_show')]
    public function show($slug, SportRepository $sportRepository, ProductRepository $productRepository): Response
    {
        //On récupère l'id et le nom du sport dans le slug
        $id = stristr($slug, '-', true);
        $title = stristr($slug, '-', false);
        $title = substr($title,1);

        return $this->render('front/sport/show.html.twig', [
            'sport' => $sportRepository->findOneBy(['id'=>$id,'title'=>$title]),
            'sports' => $sportRepository->findBy(['displayMenu'=>1],['title'=>'ASC']),
            'products' => $productRepository->findBy(['sport'=>$id],['createdAt'=>'DESC'])
        ]);
    }

}