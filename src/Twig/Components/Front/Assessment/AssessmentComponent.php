<?php
namespace App\Twig\Components\Front\Assessment;

use App\Entity\Assessment;
use App\Repository\AssessmentRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class AssessmentComponent
{
    public int $assessment_id;
    public string $type;

    public function __construct(
        private AssessmentRepository $assessmentRepository,
    ){
    }

    /**
     * @return Assessment
     */
    public function getAssessment(): Assessment
    {
        return $this->assessmentRepository->find($this->assessment_id);
    }
}