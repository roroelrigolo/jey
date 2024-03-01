<?php

namespace App\Controller\Admin;

use App\Entity\Sport;
use App\Entity\User;
use App\Form\Admin\SportFormType;
use App\Form\Admin\UserFormType;
use App\Repository\SportRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_admin_user')]
    public function index(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";

        $users = $userRepository->findBy([],[$filter=>$order]);
        $datas = [];

        foreach ($users as $user){
            $array = [
                $user->getId(),
                $user->getName(),
                $user->getFirstName(),
                $user->getPseudo(),
                $user->getEmail(),
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('admin/user/user.html.twig', [
            'datas' => $datas
        ]);
    }

    #[Route('/new', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(array("ROLE_USER"));
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $userRepository->add($user);
            $id = $user->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_user', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_user_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $userRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('password')->getData()){
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
            }
            $userRepository->add($user);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_user', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_user_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, UserRepository $userRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $userRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }
        return $this->redirectToRoute('app_admin_user', [], Response::HTTP_SEE_OTHER);
    }
}