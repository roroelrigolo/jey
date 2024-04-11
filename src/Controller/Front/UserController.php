<?php

namespace App\Controller\Front;

use App\Entity\Image;
use App\Enum;
use App\Form\Front\AccountFormType;
use App\Form\Front\PasswordFormType;
use App\Repository\ImageRepository;
use App\Repository\NotificationTypeRepository;
use App\Repository\UserRepository;
use App\Service\ImageOptimizer;
use App\Service\PasswordConfirmation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{

    #[Route('/myaccount', name: 'app_front_user_account', methods: ['GET', 'POST'])]
    public function account(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('front/user/account/account.html.twig', [
            'justMobile' => true
        ]);
    }

    #[Route('/myaccount/profile', name: 'app_front_user_account_profile', methods: ['GET', 'POST'])]
    public function account_profile(Request $request, UserRepository $userRepository, NotificationTypeRepository $notificationTypeRepository,
                                    ImageRepository $imageRepository, ImageOptimizer $imageOptimizer): Response
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
                $tmpFilePath = $_FILES['profil_picture']['tmp_name'];
                if ($tmpFilePath != ""){
                    $newFilePath = "./upload/user/img/" . $_FILES['profil_picture']['name'];
                    if($_FILES['profil_picture']['size'] < 5000000){
                        if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                            if($imageRepository->findOneBy(['user'=>$user])){
                                $oldImage = $imageRepository->findOneBy(['user'=>$user]);
                                $oldImagePath = "./upload/user/img/" . $oldImage->getUrl();
                                if (file_exists($oldImagePath)) {
                                    unlink($oldImagePath);
                                }
                                $imageRepository->remove($oldImage);
                            }

                            $image = new Image();
                            $image->setTitle($_FILES['profil_picture']['name']);
                            $image->setUrl($_FILES['profil_picture']['name']);
                            $image->setType('user');
                            $image->setUser($user);
                            $imageRepository->add($image);

                            $extention = '.'.pathinfo($image->getUrl(), PATHINFO_EXTENSION);
                            $newNameImage = 'image_'.$image->getId().'_user_'.$user->getId().$extention;
                            rename($newFilePath,'./upload/user/img/'.$newNameImage);
                            $image->setTitle($newNameImage);
                            $image->setUrl($newNameImage);
                            $imageRepository->add($image);

                            $imageOptimizer->resize('user','./upload/user/img/'.$newNameImage);

                            $user->setUpdatedAt(new \DateTimeImmutable());
                            $this->addFlash('success', 'Compte modifié avec succès');
                            $userRepository->add($user);
                        }
                    }
                    else {
                        $this->addFlash('danger', 'Votre photo de profil doit faire moins de 5mo');
                    }
                }
                else {
                    $user->setUpdatedAt(new \DateTimeImmutable());
                    $this->addFlash('success', 'Compte modifié avec succès');
                    $userRepository->add($user);
                }

            }
        }

        $notificationsType = $notificationTypeRepository->findAll();
        $upNotification = false;
        if ($_POST != null){
            foreach ($notificationsType as $notificationType){
                if(isset($_POST['notification_type-'.$notificationType->getId()])) {
                    $upNotification = true;
                    $checkbox = $_POST['notification_type-'.$notificationType->getId()];
                    if ($checkbox){
                        $user->addNotificationsType($notificationType);
                    }
                    else {
                        $user->removeNotificationsType($notificationType);
                    }
                }
            }
            if($upNotification) {
                $user->setUpdatedAt(new \DateTimeImmutable());
                $this->addFlash('success', 'Compte modifié avec succès');
                $userRepository->add($user);
            }
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

    #[Route('/myaccount/alert', name: 'app_front_user_account_alert')]
    public function account_alert(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('front/user/account/account_alert.html.twig', [
        ]);
    }

    #[Route('/myaccount/password', name: 'app_front_user_account_password', methods: ['GET', 'POST'])]
    public function account_password(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, PasswordConfirmation $passwordConfirmation): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $form = $this->createForm(PasswordFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();
            if($passwordConfirmation->isValid($password, $confirmPassword) != 'Valid'){
                $this->addFlash('danger', $passwordConfirmation->isValid($password, $password));
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

    #[Route('/myaccount/parameters', name: 'app_front_user_account_parameters', methods: ['GET', 'POST'])]
    public function account_parameters(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('front/user/account/account_parameters.html.twig', [
            'justMobile' => true
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