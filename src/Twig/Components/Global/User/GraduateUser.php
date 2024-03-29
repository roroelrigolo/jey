<?php
namespace App\Twig\Components\Global\User;

use App\Repository\UserRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class GraduateUser
{
    public string $id;

    public function __construct(
        private UserRepository $userRepository
    ){
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        $total = 0;
        $user = $this->userRepository->find($this->id);
        $assessments = $user->getAssessmentsRecipient();

        foreach ($assessments as $assessment){
            $total += $assessment->getValue();
        }
        return round($total/count($assessments));
    }

    /**
     * @return int
     */
    public function getCountReviews(): int
    {
        $user = $this->userRepository->find($this->id);
        $assessments = $user->getAssessmentsRecipient();
        return count($assessments);
    }
}