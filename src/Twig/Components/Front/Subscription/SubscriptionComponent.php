<?php
namespace App\Twig\Components\Front\Subscription;

use App\Entity\ProductLike;
use App\Entity\Subscription;
use App\Entity\User;
use App\Repository\ProductLikeRepository;
use App\Repository\ProductRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use App\Service\NotificationService;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
class SubscriptionComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public User $user;

    #[LiveProp(writable: true)]
    public User $account;

    public bool $isSubscribe;

    public function __construct(
        private ProductLikeRepository $productLikeRepository,
        private ProductRepository $productRepository,
        private UserRepository $userRepository,
        private SubscriptionRepository $subscriptionRepository,
        private NotificationService $notificationService
    ) {
    }


    /**
     * @return bool
     */
    public function isSubscribe(): bool
    {
        $subscribtion = $this->subscriptionRepository->findOneBy(['subscriber'=>$this->user, 'account'=>$this->account]);
        if($subscribtion != null){
            $this->isSubscribe = true;
        }
        else {
            $this->isSubscribe = false;
        }
        return $this->isSubscribe;
    }

    #[LiveAction]
    public function add()
    {
        $subscribtion = new Subscription();
        $subscribtion->setSubscriber($this->user);
        $subscribtion->setAccount($this->account);
        $subscribtion->setCreatedAt(new \DateTimeImmutable());
        $subscribtion->setUpdatedAt(new \DateTimeImmutable());
        $this->subscriptionRepository->add($subscribtion);
        $this->isSubscribe = true;
        $this->notificationService->addNotificationSubscribe($this->account, $this->user);
    }

    #[LiveAction]
    public function delete()
    {
        $subscribtion = $this->subscriptionRepository->findOneBy(['subscriber'=>$this->user, 'account'=>$this->account]);
        if($subscribtion != null){
            $this->notificationService->deleteNotificationSubscribe($this->account, $this->user);
            $this->subscriptionRepository->remove($subscribtion);
        }
        $this->isSubscribe = false;
    }
}