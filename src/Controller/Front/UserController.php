<?php

namespace App\Controller\Front;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
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