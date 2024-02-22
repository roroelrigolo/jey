<?php

namespace App\Controller\Admin;

use App\Entity\Color;
use App\Entity\Team;
use App\Form\Admin\ColorFormType;
use App\Form\Admin\TeamFormType;
use App\Repository\ColorRepository;
use App\Repository\LeagueRepository;
use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/color')]
class ColorController extends AbstractController
{
    #[Route('/', name: 'app_admin_color')]
    public function index(ColorRepository $colorRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";

        $colors = $colorRepository->findBy([],[$filter=>$order]);
        $datas = [];

        for ($i=0;$i<count($colors);$i++){
            $array = [
                $colors[$i]->getId(),
                $colors[$i]->getValue(),
                $colors[$i]->getTitle(),
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('admin/color/color.html.twig', [
            'datas' => $datas
        ]);
    }

    #[Route('/new', name: 'app_admin_color_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ColorRepository $colorRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $color = new Color();
        $form = $this->createForm(ColorFormType::class, $color);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $colorRepository->add($color);

            $id = $color->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_color', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_color_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/color/new.html.twig', [
            'color' => $color,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_color_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ColorRepository $colorRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $color = $colorRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(ColorFormType::class, $color);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $colorRepository->add($color);

            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_color', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_color_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/color/edit.html.twig', [
            'color' => $color,
            'form' => $form->createView(),
        ]);
    }
}