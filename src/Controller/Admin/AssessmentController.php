<?php

namespace App\Controller\Admin;

use App\Entity\Assessment;
use App\Entity\Reply;
use App\Form\Admin\AssessmentFormType;
use App\Form\Admin\ReplyFormType;
use App\Repository\AssessmentRepository;
use App\Repository\ReplyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/assessment')]
class AssessmentController extends AbstractController
{
    #[Route('/', name: 'app_admin_assessment')]
    public function index(AssessmentRepository $assessmentRepository, ReplyRepository $replyRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filter = isset($_GET["filter"]) ? $_GET["filter"] : 'id';
        $order = isset($_GET["order"]) ? $_GET["order"] : "DESC";
        
        $assessments = $assessmentRepository->findBy([],[$filter=>$order]);
        $datas = [];

        foreach ($assessments as $assessment){
            $reply = $replyRepository->findOneBy(['assessment' => $assessment]) ? 'Oui' : 'Non';
            $array = [
                $assessment->getId(),
                substr($assessment->getContent(), 0, 50).'...',
                $assessment->getValue().'<i class="text-secondary fa-solid fa-star"></i>',
                $assessment->getDepositor()->getPseudo(),
                $assessment->getRecipient()->getPseudo(),
                $assessment->getCreatedAt()->format('d/m/Y'),
                $assessment->getUpdatedAt()->format('d/m/Y'),
                $reply,
                '<i class="fa-light fa-pen-to-square"></i>'
            ];
            array_push($datas,$array);
        }

        return $this->render('admin/assessment/assessment.html.twig', [
            'datas' => $datas,
        ]);
    }

    #[Route('/new', name: 'app_admin_assessment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AssessmentRepository $assessmentRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $assessment = new Assessment();
        $form = $this->createForm(AssessmentFormType::class, $assessment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $assessment->setCreatedAt(new \DateTimeImmutable());
            $assessment->setUpdatedAt(new \DateTimeImmutable());
            $assessmentRepository->add($assessment);

            $id = $assessment->getId();
            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_assessment', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_assessment_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/assessment/new.html.twig', [
            'assessment' => $assessment,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_assessment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AssessmentRepository $assessmentRepository, ReplyRepository $replyRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $assessment = $assessmentRepository->findOneBy(['id'=>$id]);
        $form = $this->createForm(AssessmentFormType::class, $assessment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $assessment->setUpdatedAt(new \DateTimeImmutable());
            $assessmentRepository->add($assessment);

            if( $_POST['submit'] == "Enregistrer"){
                return $this->redirectToRoute('app_admin_assessment', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_admin_assessment_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
            }
        }

        $reply = $replyRepository->findOneBy(['assessment'=>$assessment]);
        if($reply){
            $form_reply = $this->createForm(ReplyFormType::class, $reply);
            $form_reply->handleRequest($request);
            if ($form_reply->isSubmitted() && $form_reply->isValid()) {
                $reply->setUpdatedAt(new \DateTimeImmutable());
                $replyRepository->add($reply);

                if( $_POST['submit'] == "Enregistrer"){
                    return $this->redirectToRoute('app_admin_assessment', [], Response::HTTP_SEE_OTHER);
                }
                else {
                    return $this->redirectToRoute('app_admin_assessment_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
                }
            }
        }
        else {
            $reply = new Reply();
            $form_reply = $this->createForm(ReplyFormType::class, $reply);
            $form_reply->handleRequest($request);
            if ($form_reply->isSubmitted() && $form_reply->isValid()) {
                $reply->setCreatedAt(new \DateTimeImmutable());
                $reply->setUpdatedAt(new \DateTimeImmutable());
                $reply->setAssessment($assessment);
                $reply->setDepositor($assessment->getRecipient());
                $replyRepository->add($reply);

                if( $_POST['submit'] == "Enregistrer"){
                    return $this->redirectToRoute('app_admin_assessment', [], Response::HTTP_SEE_OTHER);
                }
                else {
                    return $this->redirectToRoute('app_admin_assessment_edit', ['id'=>$id], Response::HTTP_SEE_OTHER);
                }
            }
        }

        return $this->render('admin/assessment/edit.html.twig', [
            'assessment' => $assessment,
            'reply' => $reply,
            'form' => $form->createView(),
            'form_reply' => $form_reply->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_assessment_reply_delete', methods: ['POST'])]
    public function deleteReply(Request $request, ReplyRepository $replyRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $reply = $replyRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$reply->getId(), $request->request->get('_token'))) {
            $reply->setAssessment(null);
            $replyRepository->remove($reply);
        }
        return $this->redirectToRoute('app_admin_assessment', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_admin_assessment_delete', methods: ['POST'])]
    public function delete(Request $request, AssessmentRepository $assessmentRepository, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $assessment = $assessmentRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$assessment->getId(), $request->request->get('_token'))) {
            $assessmentRepository->remove($assessment);
        }
        return $this->redirectToRoute('app_admin_assessment', [], Response::HTTP_SEE_OTHER);
    }
}