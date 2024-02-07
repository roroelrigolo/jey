<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Sport;
use App\Form\SportFormType;
use App\Repository\ImageRepository;
use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/sport')]
class SportController extends AbstractController
{
    #[Route('/', name: 'app_admin_sport')]
    public function index(SportRepository $sportRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";

        $sports = $sportRepository->findBy([],[$filter=>$order]);
        $datas = [];

        for ($i=0;$i<count($sports);$i++){
            if($sports[$i]->isDisplayMenu() == 1){
                $displayMenu = "Oui";
            }
            else{
                $displayMenu = "Non";
            }
            $array = [
                $sports[$i]->getId(),
                $sports[$i]->getTitle(),
                $displayMenu,
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('admin/sport/sport.html.twig', [
            'datas' => $datas
        ]);
    }

    #[Route('/new', name: 'app_admin_sport_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SportRepository $sportRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $sport = new Sport();
        $form = $this->createForm(SportFormType::class, $sport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sportRepository->add($sport);
            $id = $sport->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_sport', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_sport_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/sport/new.html.twig', [
            'sport' => $sport,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_sport_edit', methods: ['GET', 'POST'])]
    public function edit($id, Request $request, SportRepository $sportRepository, ImageRepository $imageRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $sport = $sportRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(SportFormType::class, $sport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $files = array_filter($_FILES['banner']['name']); //Use something similar before processing files.
            // Count the number of uploaded files in array
            $total_count = count($_FILES['banner']['name']);
            // Loop through every file
            for( $i=0 ; $i < $total_count ; $i++ ) {
                //The temp file path is obtained
                $tmpFilePath = $_FILES['banner']['tmp_name'][$i];
                //A file path needs to be present
                if ($tmpFilePath != ""){
                    //Setup our new file path
                    $newFilePath = "./upload/product/img/" . $_FILES['banner']['name'][$i];
                    //File is uploaded to temp dir
                    if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                        //Other code goes here
                    }
                }
                $image = new Image();
                $image->setTitle($_FILES['banner']['name'][$i]);
                $image->setUrl($_FILES['banner']['name'][$i]);
                $image->setType('sport');
                $image->setProduct(null);
                $imageRepository->add($image);
            }

            $sportRepository->add($sport);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_sport', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_sport_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/sport/edit.html.twig', [
            'sport' => $sport,
            'form' => $form->createView(),
        ]);
    }
}