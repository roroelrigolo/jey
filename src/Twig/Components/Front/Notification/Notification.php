<?php
namespace App\Twig\Components\Front\Notification;

use App\Repository\NotificationRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Notification
{
    public string $user_id;

    public function __construct(
        private NotificationRepository $notificationRepository
    ){
    }

    /**
     * @return array
     */
    public function getNotifications(): array
    {
        return $this->notificationRepository->findBy(['user'=>$this->user_id],['updatedAt'=>'DESC']);
    }
}