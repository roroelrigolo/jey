<?php
namespace App\Service;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use App\Repository\NotificationTypeRepository;

class NotificationService
{

    public function __construct(
        private NotificationTypeRepository $notificationTypeRepository,
        private NotificationRepository $notificationRepository
    )
    {
    }

    public function addNotificationMessage($user, $message) {
        $notifcation = new Notification();
        $notifcation->setType($this->notificationTypeRepository->find(1));
        $notifcation->setUser($user);
        $notifcation->setMessage($message);
        $notifcation->setView(0);
        $notifcation->setCreatedAt(new \DateTimeImmutable());
        $notifcation->setUpdatedAt(new \DateTimeImmutable());

        $this->notificationRepository->add($notifcation);
    }

    public function addNotificationSendAlert($user) {
        $notifcation = new Notification();
        $notifcation->setType($this->notificationTypeRepository->find(6));
        $notifcation->setUser($user);
        $notifcation->setView(0);
        $notifcation->setCreatedAt(new \DateTimeImmutable());
        $notifcation->setUpdatedAt(new \DateTimeImmutable());

        $this->notificationRepository->add($notifcation);
    }
}