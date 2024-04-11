<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginAuthenticator;
use App\Service\PasswordConfirmation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator,
                             LoginAuthenticator $authenticator, EntityManagerInterface $entityManager, UserRepository $userRepository, PasswordConfirmation $passwordConfirmation): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userPseudoExist = $userRepository->findOneBy(['pseudo'=>$form->get('pseudo')->getData()]);
            $userEmailExist = $userRepository->findOneBy(['email'=>$form->get('email')->getData()]);
            $password = $form->get('password')->getData();
            if($userPseudoExist != null){
                $this->addFlash('danger', 'Désolé, vous ne pouvez pas utiliser ce pseudo car il est déjà utilisé par un autre utilisateur');
            }
            elseif ($userEmailExist != null){
                $this->addFlash('danger', 'Désolé, vous ne pouvez pas utiliser cette adresse mail car elle est déjà utilisée par un autre utilisateur');
            }
            elseif ($passwordConfirmation->isValid($password, $password) != 'Valid'){
                $this->addFlash('danger', $passwordConfirmation->isValid($password, $password));
            }
            else {
                $user->setRoles(array("ROLE_USER"));
                $user->setCreatedAt(new \DateTimeImmutable());
                $user->setUpdatedAt(new \DateTimeImmutable());
                $user->setLastConnexion(new \DateTimeImmutable());
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $userRepository->add($user);

                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );
            }
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
