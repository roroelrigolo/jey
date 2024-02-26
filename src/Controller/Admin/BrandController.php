<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Entity\Sport;
use App\Form\Admin\BrandFormType;
use App\Form\Admin\SportFormType;
use App\Repository\BrandRepository;
use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/brand')]
class BrandController extends AbstractController
{
    #[Route('/', name: 'app_admin_brand')]
    public function index(BrandRepository $brandRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";
        
        $brands = $brandRepository->findBy([],[$filter=>$order]);
        $datas = [];

        for ($i=0;$i<count($brands);$i++){
            $available = ($brands[$i]->isAvailable() == 1) ? "Oui" : "Non";
            $array = [
                $brands[$i]->getId(),
                $brands[$i]->getTitle(),
                $available,
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('admin/brand/brand.html.twig', [
            'datas' => $datas
        ]);
    }

    #[Route('/new', name: 'app_admin_brand_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BrandRepository $brandRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $brand = new Brand();
        $form = $this->createForm(BrandFormType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brandRepository->add($brand);
            $id = $brand->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_brand', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_brand_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/brand/new.html.twig', [
            'brand' => $brand,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_brand_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BrandRepository $brandRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $brand = $brandRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(BrandFormType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $brandRepository->add($brand);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_brand', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_brand_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/brand/edit.html.twig', [
            'brand' => $brand,
            'form' => $form->createView(),
        ]);
    }
}