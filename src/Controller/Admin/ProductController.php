<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Product;
use App\Form\Admin\ProductFormType;
use App\Repository\AlertRepository;
use App\Repository\AssessmentRepository;
use App\Repository\ColorRepository;
use App\Repository\ImageRepository;
use App\Repository\LeagueRepository;
use App\Repository\PlayerRepository;
use App\Repository\ProductLikeRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductViewRepository;
use App\Repository\TeamRepository;
use App\Repository\TextilRepository;
use Symfony\Component\Uid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_admin_product')]
    public function index(ProductRepository $productRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";

        $products = $productRepository->findBy([],[$filter=>$order]);
        $datas = [];

        foreach ($products as $product){
            $statement = $product->getStatement();
            switch ($statement) {
                case "Disponible":
                    $class = "bg-green bg-opacity-50 rounded text-greendark";
                    break;
                case "Réservé":
                    $class = "bg-orange bg-opacity-50 rounded text-orangedark";
                    break;
                case "Vendu":
                    $class = "bg-tertiary bg-opacity-50 rounded text-primary";
                    break;
                case "A valider":
                    $class = "bg-red bg-opacity-50 rounded text-reddark";
                    break;
                default:
                    $class = "";
                    break;
            }
            $statement = '<span class="w-10/12 block text-center ' . $class . ' px-2">' . $statement . '</span>';
            $array = [
                $product->getId(),
                $statement,
                $product->getTitle(),
                $product->getDescription(),
                $product->getPrice().'€',
                $product->getCreatedAt()->format('d/m/Y'),
                $product->getUpdatedAt()->format('d/m/Y'),
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('admin/product/product.html.twig', [
            'datas' => $datas
        ]);
    }

    #[Route('/new', name: 'app_admin_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository, LeagueRepository $leagueRepository, TeamRepository $teamRepository,
                        PlayerRepository $playerRepository, ImageRepository $imageRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUuid(Uuid::uuid4()->toString());
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
        }

        return $this->render('admin/product/new.html.twig', [
            'product' => $product,
            'leagues' => $leagueRepository->findAll(),
            'teams' => $teamRepository->findAll(),
            'players' => $playerRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProductRepository $productRepository, LeagueRepository $leagueRepository, TeamRepository $teamRepository,
                         PlayerRepository $playerRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $product = $productRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setUpdatedAt(new \DateTimeImmutable());
            $productRepository->add($product);

            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_product', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_product_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'leagues' => $leagueRepository->findAll(),
            'teams' => $teamRepository->findAll(),
            'players' => $playerRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_product_delete', methods: ['POST'])]
    public function delete(Request $request, ProductRepository $productRepository, ImageRepository $imageRepository, ProductViewRepository $productViewRepository,
                           ProductLikeRepository $productLikeRepository, ColorRepository $colorRepository, TextilRepository $textilRepository,
                           AlertRepository $alertRepository, AssessmentRepository $assessmentRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $product = $productRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            foreach ($product->getImages() as $image){
                $imagePath = "./upload/product/img/" . $image->getUrl();
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $imageRepository->remove($image);
            }
            foreach ($product->getProductViews() as $view){
                $productViewRepository->remove($view);
            }
            foreach ($product->getProductLikes() as $like){
                $productLikeRepository->remove($like);
            }
            foreach ($product->getAlerts() as $alert){
                $alertRepository->remove($alert);
            }
            foreach ($product->getAssessments() as $assessment){
                $assessmentRepository->remove($assessment);
            }
            $productRepository->remove($product);
        }
        return $this->redirectToRoute('app_admin_product', [], Response::HTTP_SEE_OTHER);
    }
}