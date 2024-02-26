<?php

namespace App\Controller\Front;

use App\Entity\Image;
use App\Entity\Product;
use App\Entity\Sport;
use App\Form\Front\ProductFormType;
use App\Repository\ImageRepository;
use App\Repository\LeagueRepository;
use App\Repository\PlayerRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductViewRepository;
use App\Repository\SportRepository;
use App\Repository\TeamRepository;
use App\Service\ViewService;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/new', name: 'app_front_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository, LeagueRepository $leagueRepository, TeamRepository $teamRepository,
                        PlayerRepository $playerRepository, ImageRepository $imageRepository, SportRepository $sportRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $sportProduct = $_POST['search-input-sport'] ?? null;
            if (!empty($sportProduct)) {
                $sportInBase = $sportRepository->findOneBy(['title'=>$sportProduct],[]);
                if($sportInBase == null){
                    $newSport = new Sport();
                    $newSport
                        ->setTitle(ucfirst($sportProduct))
                        ->setDisplayMenu(0)
                        ->setAvailable(0);
                    $sportRepository->add($newSport);
                    $product->setSport($newSport);
                }
                else {
                    $product->setSport($sportInBase);
                }
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {
            var_dump("test");
            /*
            $product->setUser($this->getUser());
            $product->setUuid(Uuid::uuid4()->toString());
            $product->setStatement('Disponible');
            $product->setCreatedAt(new \DateTimeImmutable());
            $product->setUpdatedAt(new \DateTimeImmutable());

            $productRepository->add($product);

            $files = array_filter($_FILES['images']['name']); //Use something similar before processing files.
            // Count the number of uploaded files in array
            $total_count = count($_FILES['images']['name']);
            // Loop through every file
            for( $i=0 ; $i < $total_count ; $i++ ) {
                //The temp file path is obtained
                $tmpFilePath = $_FILES['images']['tmp_name'][$i];
                //A file path needs to be present
                if ($tmpFilePath != ""){
                    //Setup our new file path
                    $newFilePath = "./upload/product/img/" . $_FILES['images']['name'][$i];
                    //File is uploaded to temp dir
                    if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                        //Other code goes here
                    }
                }
                $image = new Image();
                $image->setTitle($_FILES['images']['name'][$i]);
                $image->setUrl($_FILES['images']['name'][$i]);
                $image->setType('product');
                $image->setProduct($product);
                $imageRepository->add($image);
            }

            $id = $product->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_product', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_product_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
            */
        }

        return $this->render('front/product/new.html.twig', [
            'product' => $product,
            'leagues' => $leagueRepository->findAll(),
            'teams' => $teamRepository->findAll(),
            'players' => $playerRepository->findAll(),
            'sports' => $sportRepository->findBy(['displayMenu'=>1],['title'=>'ASC']),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{uuid}', name: 'app_front_product_show')]
    public function show($uuid, ProductRepository $productRepository, SportRepository $sportRepository, ProductViewRepository $productViewRepository): Response
    {
        $product = $productRepository->findOneBy(['uuid'=>$uuid]);
        $sport = $product->getSport();

        $view = new ViewService();
        $view->setViewProduct($this->getUser(),$product,$productViewRepository);

        return $this->render('front/product/show.html.twig', [
            'product' => $product,
            'productSimilary' => $productRepository->findBy(['sport'=>$sport],['created_at'=>'DESC']),
            'sports' => $sportRepository->findBy(['displayMenu'=>1],['title'=>'ASC'])
        ]);
    }

}