<?php
namespace App\Service;

use App\Entity\Alert;
use App\Repository\AlertRepository;

class AlertService
{

    public function __construct(
        private AlertRepository $alertRepository,
    )
    {
    }

    public function addAlert($depositor, $type, $relation) {
        $alert = new Alert();
        $alert->setDepositor($depositor);
        $alert->setType($type);
        if($type == "Annonce"){
            $alert->setProduct($relation);
        }
        elseif ($type == "Utilisateur"){
            $alert->setUser($relation);
        }
        $alert->setStatut('A vérifier');
        $alert->setCreatedAt(new \DateTimeImmutable());
        $alert->setUpdatedAt(new \DateTimeImmutable());

        $this->alertRepository->add($alert);
    }
}