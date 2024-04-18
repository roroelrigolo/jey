<?php

namespace App\Controller\Front;

use App\Entity\Contact;
use App\Form\Front\ContactFormType;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_front_contact', methods: ['GET', 'POST'])]
    public function new(Request $request, ContactRepository $contactRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setStatut('A traité');
            $contact->setCreatedAt(new \DateTimeImmutable());
            $contact->setUpdatedAt(new \DateTimeImmutable());
            $contactRepository->add($contact);

            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('app_front_contact', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/contact/contact.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }
}