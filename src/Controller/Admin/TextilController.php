<?php

namespace App\Controller\Admin;

use App\Entity\Textil;
use App\Form\Admin\TextilFormType;
use App\Repository\TextilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/textil')]
class TextilController extends AbstractController
{
    #[Route('/', name: 'app_admin_textil')]
    public function index(TextilRepository $textilRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";

        $textils = $textilRepository->findBy([],[$filter=>$order]);
        $datas = [];

        foreach ($textils as $textil){
            $array = [
                $textil->getId(),
                $textil->getTitle(),
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('admin/textil/textil.html.twig', [
            'datas' => $datas,
        ]);
    }

    #[Route('/new', name: 'app_admin_textil_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TextilRepository $textilRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $textil = new Textil();
        $form = $this->createForm(TextilFormType::class, $textil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $textil->setCreatedAt(new \DateTimeImmutable());
            $textil->setUpdatedAt(new \DateTimeImmutable());
            $textilRepository->add($textil);
            $id = $textil->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_textil', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_textil_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/textil/new.html.twig', [
            'textil' => $textil,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_textil_edit', methods: ['GET', 'POST'])]
    public function edit($id, Request $request, TextilRepository $textilRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $textil = $textilRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(TextilFormType::class, $textil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $textil->setUpdatedAt(new \DateTimeImmutable());
            $textilRepository->add($textil);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_textil', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_textil_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/textil/edit.html.twig', [
            'textil' => $textil,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_textil_delete', methods: ['POST'])]
    public function delete(Request $request, TextilRepository $textilRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $textil = $textilRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$textil->getId(), $request->request->get('_token'))) {
            $textilRepository->remove($textil);
        }
        return $this->redirectToRoute('app_admin_textil', [], Response::HTTP_SEE_OTHER);
    }
}