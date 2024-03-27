<?php

namespace App\Controller\Front;

use App\Entity\Assessment;
use App\Form\Front\AssessmentFormType;
use App\Repository\AssessmentRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/assessment')]
class AssessmentController extends AbstractController
{
    #[Route('/{uuid}/new', name: 'app_front_assessment_new', methods: ['GET', 'POST'])]
    public function new($uuid, Request $request, AssessmentRepository $assessmentRepository, ProductRepository $productRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $product = $productRepository->findOneBy(['uuid'=>$uuid]);
        if($product->getUser()->getId() != $this->getUser()->getId()){
            $recipient = $product->getUser();
        }
        else {
            $recipient = $product->getBuyer();
        }

        $assessment = new Assessment();
        $form = $this->createForm(AssessmentFormType::class, $assessment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $assessment->setProduct($product);
            $assessment->setDepositor($this->getUser());
            $assessment->setRecipient($recipient);
            $assessment->setCreatedAt(new \DateTimeImmutable());
            $assessment->setUpdatedAt(new \DateTimeImmutable());
            $assessmentRepository->add($assessment);

            return $this->redirectToRoute('app_admin_assessment', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/assessment/new.html.twig', [
            'assessment' => $assessment,
            'recipient' => $recipient,
            'form' => $form->createView(),
        ]);
    }

}