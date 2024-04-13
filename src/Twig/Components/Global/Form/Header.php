<?php
namespace App\Twig\Components\Global\Form;

use App\Entity\Alert;
use App\Entity\Assessment;
use App\Entity\Brand;
use App\Entity\Color;
use App\Entity\Conversation;
use App\Entity\Department;
use App\Entity\League;
use App\Entity\Notification;
use App\Entity\NotificationType;
use App\Entity\Player;
use App\Entity\Product;
use App\Entity\Reply;
use App\Entity\Sport;
use App\Entity\Subscription;
use App\Entity\Team;
use App\Entity\Textil;
use App\Entity\User;
use App\Repository\AlertRepository;
use App\Repository\AssessmentRepository;
use App\Repository\BrandRepository;
use App\Repository\ColorRepository;
use App\Repository\ConversationRepository;
use App\Repository\DepartmentRepository;
use App\Repository\LeagueRepository;
use App\Repository\NotificationRepository;
use App\Repository\NotificationTypeRepository;
use App\Repository\PlayerRepository;
use App\Repository\ProductRepository;
use App\Repository\ReplyRepository;
use App\Repository\SportRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\TeamRepository;
use App\Repository\TextilRepository;
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
        private AlertRepository $alertRepository,
        private ColorRepository $colorRepository,
        private BrandRepository $brandRepository,
        private PlayerRepository $playerRepository,
        private TeamRepository $teamRepository,
        private LeagueRepository $leagueRepository,
        private SportRepository $sportRepository,
        private ProductRepository $productRepository,
        private AssessmentRepository $assessmentRepository,
        private ReplyRepository $replyRepository,
        private ConversationRepository $conversationRepository,
        private NotificationRepository $notificationRepository,
        private NotificationTypeRepository $notificationTypeRepository,
        private TextilRepository $textilRepository,
        private SubscriptionRepository $subscriptionRepository,
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
     * @return Department
     */
    public function getDepartment(): Department
    {
        return $this->departmentRepository->find($this->id_entity);
    }

    /**
     * @return Alert
     */
    public function getAlert(): Alert
    {
        return $this->alertRepository->find($this->id_entity);
    }

    /**
     * @return Color
     */
    public function getColor(): Color
    {
        return $this->colorRepository->find($this->id_entity);
    }

    /**
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brandRepository->find($this->id_entity);
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->playerRepository->find($this->id_entity);
    }

    /**
     * @return Team
     */
    public function getTeam(): Team
    {
        return $this->teamRepository->find($this->id_entity);
    }

    /**
     * @return League
     */
    public function getLeague(): League
    {
        return $this->leagueRepository->find($this->id_entity);
    }

    /**
     * @return Sport
     */
    public function getSport(): Sport
    {
        return $this->sportRepository->find($this->id_entity);
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->productRepository->find($this->id_entity);
    }

    /**
     * @return Assessment
     */
    public function getAssessment(): Assessment
    {
        return $this->assessmentRepository->find($this->id_entity);
    }

    /**
     * @return Reply
     */
    public function getReply(): Reply
    {
        return $this->replyRepository->find($this->id_entity);
    }

    /**
     * @return Conversation
     */
    public function getConversation(): Conversation
    {
        return $this->conversationRepository->find($this->id_entity);
    }

    /**
     * @return Notification
     */
    public function getNotification(): Notification
    {
        return $this->notificationRepository->find($this->id_entity);
    }

    /**
     * @return NotificationType
     */
    public function getNotificationType(): NotificationType
    {
        return $this->notificationTypeRepository->find($this->id_entity);
    }

    /**
     * @return Textil
     */
    public function getTextil(): Textil
    {
        return $this->textilRepository->find($this->id_entity);
    }

    /**
     * @return Subscription
     */
    public function getSubscription(): Subscription
    {
        return $this->subscriptionRepository->find($this->id_entity);
    }
}