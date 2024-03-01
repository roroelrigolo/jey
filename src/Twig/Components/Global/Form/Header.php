<?php
namespace App\Twig\Components\Global\Form;

use App\Entity\Department;
use App\Entity\User;
use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Header
{
    public string $title;
    public string $id_entity;
    public string $type;

    public function __construct(
        private UserRepository $userRepository,
        private DepartmentRepository $departmentRepository,
    ){
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->userRepository->find($this->id_entity);
    }

    /**
     * @return User
     */
    public function getDepartment(): Department
    {
        return $this->departmentRepository->find($this->id_entity);
    }
}