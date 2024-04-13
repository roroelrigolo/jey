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
        $notification = new Notification();
        $notification->setType($this->notificationTypeRepository->find(1));
        $notification->setUser($user);
        $notification->setMessage($message);
        $notification->setView(0);
        $notification->setCreatedAt(new \DateTimeImmutable());
        $notification->setUpdatedAt(new \DateTimeImmutable());

        $this->notificationRepository->add($notification);
    }

    public function addNotificationSendAlert($user) {
        $notification = new Notification();
        $notification->setType($this->notificationTypeRepository->find(6));
        $notification->setUser($user);
        $notification->setView(0);
        $notification->setCreatedAt(new \DateTimeImmutable());
        $notification->setUpdatedAt(new \DateTimeImmutable());

        $this->notificationRepository->add($notification);
    }

    public function addNotificationSubscribe($user, $subscriber) {
        $notification = new Notification();
        $notification->setType($this->notificationTypeRepository->find(7));
        $notification->setUser($user);
        $notification->setSubscriber($subscriber);
        $notification->setView(0);
        $notification->setCreatedAt(new \DateTimeImmutable());
        $notification->setUpdatedAt(new \DateTimeImmutable());

        $this->notificationRepository->add($notification);
    }

    public function deleteNotificationSubscribe($user, $subscriber) {
        $notification = $this->notificationRepository->findOneBy(['user'=>$user, 'subscriber'=>$subscriber]);
        if($notification != null){
            $this->notificationRepository->remove($notification);
        }
    }
}