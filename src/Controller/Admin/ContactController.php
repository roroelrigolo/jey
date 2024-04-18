<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Form\Admin\ContactFormType;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/contact')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'app_admin_contact')]
    public function index(ContactRepository $contactRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";
        
        $contacts = $contactRepository->findBy([],[$filter=>$order]);
        $datas = [];

        foreach ($contacts as $contact){
            $array = [
                $contact->getId(),
                $contact->getEmail(),
                $contact->getPhone(),
                $contact->getSubject(),
                $contact->getCreatedAt()->format('d/m/Y'),
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('admin/contact/contact.html.twig', [
            'datas' => $datas
        ]);
    }

    #[Route('/new', name: 'app_admin_contact_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContactRepository $contactRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setCreatedAt(new \DateTimeImmutable());
            $contact->setUpdatedAt(new \DateTimeImmutable());
            $contactRepository->add($contact);
            $id = $contact->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_contact', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_contact_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/contact/new.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_contact_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ContactRepository $contactRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $contact = $contactRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setUpdatedAt(new \DateTimeImmutable());
            $contactRepository->add($contact);
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_contact', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_contact_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/contact/edit.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_contact_delete', methods: ['POST'])]
    public function delete(Request $request, ContactRepository $contactRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $contact = $contactRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $contactRepository->remove($contact);
        }
        return $this->redirectToRoute('app_admin_contact', [], Response::HTTP_SEE_OTHER);
    }
}