<?php

namespace App\Controller\Admin;

use App\Entity\Department;
use App\Form\Admin\DepartmentFormType;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/department')]
class DepartmentController extends AbstractController
{
    #[Route('/', name: 'app_admin_department')]
    public function index(DepartmentRepository $departmentRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";

        $departments = $departmentRepository->findBy([],[$filter=>$order]);
        $datas = [];

        for ($i=0;$i<count($departments);$i++){
            $array = [
                $departments[$i]->getId(),
                $departments[$i]->getCode(),
                $departments[$i]->getTitle(),
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('admin/department/department.html.twig', [
            'datas' => $datas,
        ]);
    }

    #[Route('/new', name: 'app_admin_department_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DepartmentRepository $departmentRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $department = new Department();
        $form = $this->createForm(DepartmentFormType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $departmentRepository->add($department);
            $id = $department->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_department', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_department_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/department/new.html.twig', [
            'department' => $department,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_department_edit', methods: ['GET', 'POST'])]
    public function edit($id, Request $request, DepartmentRepository $departmentRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $department = $departmentRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(DepartmentFormType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $departmentRepository->add($department);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_department', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_department_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/department/edit.html.twig', [
            'department' => $department,
            'form' => $form->createView(),
        ]);
    }
}