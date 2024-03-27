<?php

namespace App\Controller\Front;

use App\Enum;
use App\Form\Front\AccountFormType;
use App\Form\Front\PasswordFormType;
use App\Repository\NotificationTypeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/myaccount/profile', name: 'app_front_user_account_profile', methods: ['GET', 'POST'])]
    public function account_profile(Request $request, UserRepository $userRepository, NotificationTypeRepository $notificationTypeRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $form = $this->createForm(AccountFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userPseudoExist = $userRepository->findOneBy(['pseudo'=>$form->get('pseudo')->getData()]);
            if($userPseudoExist != null && $userPseudoExist->getId() != $user->getId()){
                $this->addFlash('danger', 'Désolé, vous ne pouvez pas utiliser ce pseudo car il est déjà utilisé par un autre utilisateur');
                return $this->redirectToRoute('app_front_user_account_profile', [], Response::HTTP_SEE_OTHER);
            }
            else {
                $user->setUpdatedAt(new \DateTimeImmutable());
                $this->addFlash('success', 'Compte modifié avec succès');
                $userRepository->add($user);
            }
        }

        $notificationsType = $notificationTypeRepository->findAll();
        if ($_POST != null){
            foreach ($notificationsType as $notificationType){
                $checkbox = $_POST['notification_type-'.$notificationType->getId()];
                if ($checkbox){
                    $user->addNotificationsType($notificationType);
                }
                else {
                    $user->removeNotificationsType($notificationType);
                }
            }
            $user->setUpdatedAt(new \DateTimeImmutable());
            $this->addFlash('success', 'Compte modifié avec succès');
            $userRepository->add($user);
        }

        return $this->render('front/user/account/account_profile.html.twig', [
            'form' => $form->createView(),
            'notificationsType' => $notificationsType,
            'notificationCategorys' => Enum::$notification_categorys
        ]);
    }


    #[Route('/myaccount/product', name: 'app_front_user_account_product')]
    public function account_product(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('front/user/account/account_product.html.twig', [
        ]);
    }

    #[Route('/myaccount/assessment', name: 'app_front_user_account_assessment')]
    public function account_assessment(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('front/user/account/account_assessment.html.twig', [
        ]);
    }

    #[Route('/myaccount/password', name: 'app_front_user_account_password', methods: ['GET', 'POST'])]
    public function account_password(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $form = $this->createForm(PasswordFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('password')->getData() != $form->get('confirmPassword')->getData()){
                $this->addFlash('danger', 'Les mots de passe ne sont pas identiques, veuillez essayer à nouveau');
            }
            elseif (strlen($form->get('password')->getData()) <= 6){
                 $this->addFlash('danger', 'Le mot de passe doit contenir au minimum 6 caractères');
            }
            elseif (!preg_match('/[0-9]/', $form->get('password')->getData())){
                $this->addFlash('danger', 'Le mot de passe doit contenir au minimum 1 chiffre [0-9]');
            }
            elseif (!preg_match('/[a-zA-Z]/', $form->get('password')->getData())){
                $this->addFlash('danger', 'Le mot de passe doit contenir au minimum 1 lettre [a-z] ou [A-Z]');
            }
            elseif (!preg_match('/[^a-zA-Z0-9]/', $form->get('password')->getData())){
                $this->addFlash('danger', 'Le mot de passe doit contenir au minimum 1 caractère spécial');
            }
            else {
                $user->setUpdatedAt(new \DateTimeImmutable());
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $this->addFlash('success', 'Mot de passe modifié avec succès');
                $userRepository->add($user);
            }
        }

        return $this->render('front/user/account/account_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{pseudo}', name: 'app_front_user_show')]
    public function show($pseudo, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['pseudo'=>$pseudo]);
        if(empty($user)){
            return $this->render('front/user/none.html.twig', [
                'pseudo' => $pseudo
            ]);
        }
        else {
            return $this->render('front/user/show.html.twig', [
                'user' => $user
            ]);
        }
    }
}