<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Product::class)]
    private Collection $products;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ProductLike::class)]
    private Collection $productLikes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ProductView::class)]
    private Collection $productViews;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Department $location = null;

    #[ORM\OneToMany(mappedBy: 'depositor', targetEntity: Assessment::class)]
    private Collection $assessments_depositor;

    #[ORM\OneToMany(mappedBy: 'recipient', targetEntity: Assessment::class)]
    private Collection $assessments_recipient;

    #[ORM\OneToMany(mappedBy: 'depositor', targetEntity: Reply::class)]
    private Collection $responses;

    #[ORM\OneToMany(mappedBy: 'depositor', targetEntity: Alert::class)]
    private Collection $alerts_depositor;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Alert::class)]
    private Collection $alerts_user;

    #[ORM\ManyToMany(targetEntity: Conversation::class, mappedBy: 'users')]
    private Collection $conversations;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notification::class)]
    private Collection $notifications;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: NotificationType::class, inversedBy: 'users')]
    private Collection $notifications_type;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CancelBook::class)]
    private Collection $cancelBooks;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $lastConnexion = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Image::class)]
    private Collection $images;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->productLikes = new ArrayCollection();
        $this->productViews = new ArrayCollection();
        $this->assessments_depositor = new ArrayCollection();
        $this->assessments_recipient = new ArrayCollection();
        $this->responses = new ArrayCollection();
        $this->alerts_depositor = new ArrayCollection();
        $this->alerts_user = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->notifications_type = new ArrayCollection();
        $this->cancelBooks = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setUser($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getUser() === $this) {
                $product->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductLike>
     */
    public function getProductLikes(): Collection
    {
        return $this->productLikes;
    }

    public function addProductLike(ProductLike $productLike): static
    {
        if (!$this->productLikes->contains($productLike)) {
            $this->productLikes->add($productLike);
            $productLike->setUser($this);
        }

        return $this;
    }

    public function removeProductLike(ProductLike $productLike): static
    {
        if ($this->productLikes->removeElement($productLike)) {
            // set the owning side to null (unless already changed)
            if ($productLike->getUser() === $this) {
                $productLike->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductView>
     */
    public function getProductViews(): Collection
    {
        return $this->productViews;
    }

    public function addProductView(ProductView $productView): static
    {
        if (!$this->productViews->contains($productView)) {
            $this->productViews->add($productView);
            $productView->setUser($this);
        }

        return $this;
    }

    public function removeProductView(ProductView $productView): static
    {
        if ($this->productViews->removeElement($productView)) {
            // set the owning side to null (unless already changed)
            if ($productView->getUser() === $this) {
                $productView->setUser(null);
            }
        }

        return $this;
    }

    public function getLocation(): ?Department
    {
        return $this->location;
    }

    public function setLocation(?Department $location): static
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection<int, Assessment>
     */
    public function getAssessmentsDepositor(): Collection
    {
        return $this->assessments_depositor;
    }

    public function addAssessmentsDepositor(Assessment $assessmentsDepositor): static
    {
        if (!$this->assessments_depositor->contains($assessmentsDepositor)) {
            $this->assessments_depositor->add($assessmentsDepositor);
            $assessmentsDepositor->setDepositor($this);
        }

        return $this;
    }

    public function removeAssessmentsDepositor(Assessment $assessmentsDepositor): static
    {
        if ($this->assessments_depositor->removeElement($assessmentsDepositor)) {
            // set the owning side to null (unless already changed)
            if ($assessmentsDepositor->getDepositor() === $this) {
                $assessmentsDepositor->setDepositor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Assessment>
     */
    public function getAssessmentsRecipient(): Collection
    {
        return $this->assessments_recipient;
    }

    public function addAssessmentsRecipient(Assessment $assessmentsRecipient): static
    {
        if (!$this->assessments_recipient->contains($assessmentsRecipient)) {
            $this->assessments_recipient->add($assessmentsRecipient);
            $assessmentsRecipient->setRecipient($this);
        }

        return $this;
    }

    public function removeAssessmentsRecipient(Assessment $assessmentsRecipient): static
    {
        if ($this->assessments_recipient->removeElement($assessmentsRecipient)) {
            // set the owning side to null (unless already changed)
            if ($assessmentsRecipient->getRecipient() === $this) {
                $assessmentsRecipient->setRecipient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reply>
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(Reply $response): static
    {
        if (!$this->responses->contains($response)) {
            $this->responses->add($response);
            $response->setDepositor($this);
        }

        return $this;
    }

    public function removeResponse(Reply $response): static
    {
        if ($this->responses->removeElement($response)) {
            // set the owning side to null (unless already changed)
            if ($response->getDepositor() === $this) {
                $response->setDepositor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Alert>
     */
    public function getAlertsDepositor(): Collection
    {
        return $this->alerts_depositor;
    }

    public function addAlertsDepositor(Alert $alertsDepositor): static
    {
        if (!$this->alerts_depositor->contains($alertsDepositor)) {
            $this->alerts_depositor->add($alertsDepositor);
            $alertsDepositor->setDepositor($this);
        }

        return $this;
    }

    public function removeAlertsDepositor(Alert $alertsDepositor): static
    {
        if ($this->alerts_depositor->removeElement($alertsDepositor)) {
            // set the owning side to null (unless already changed)
            if ($alertsDepositor->getDepositor() === $this) {
                $alertsDepositor->setDepositor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Alert>
     */
    public function getAlertsUser(): Collection
    {
        return $this->alerts_user;
    }

    public function addAlertsUser(Alert $alertsUser): static
    {
        if (!$this->alerts_user->contains($alertsUser)) {
            $this->alerts_user->add($alertsUser);
            $alertsUser->setUser($this);
        }

        return $this;
    }

    public function removeAlertsUser(Alert $alertsUser): static
    {
        if ($this->alerts_user->removeElement($alertsUser)) {
            // set the owning side to null (unless already changed)
            if ($alertsUser->getUser() === $this) {
                $alertsUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): static
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations->add($conversation);
            $conversation->addUser($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): static
    {
        if ($this->conversations->removeElement($conversation)) {
            $conversation->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setAuthor($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getAuthor() === $this) {
                $message->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, NotificationType>
     */
    public function getNotificationsType(): Collection
    {
        return $this->notifications_type;
    }

    public function addNotificationsType(NotificationType $notificationsType): static
    {
        if (!$this->notifications_type->contains($notificationsType)) {
            $this->notifications_type->add($notificationsType);
        }

        return $this;
    }

    public function removeNotificationsType(NotificationType $notificationsType): static
    {
        $this->notifications_type->removeElement($notificationsType);

        return $this;
    }

    /**
     * @return Collection<int, CancelBook>
     */
    public function getCancelBooks(): Collection
    {
        return $this->cancelBooks;
    }

    public function addCancelBook(CancelBook $cancelBook): static
    {
        if (!$this->cancelBooks->contains($cancelBook)) {
            $this->cancelBooks->add($cancelBook);
            $cancelBook->setUser($this);
        }

        return $this;
    }

    public function removeCancelBook(CancelBook $cancelBook): static
    {
        if ($this->cancelBooks->removeElement($cancelBook)) {
            // set the owning side to null (unless already changed)
            if ($cancelBook->getUser() === $this) {
                $cancelBook->setUser(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLastConnexion(): ?\DateTimeInterface
    {
        return $this->lastConnexion;
    }

    public function setLastConnexion(\DateTimeInterface $lastConnexion): static
    {
        $this->lastConnexion = $lastConnexion;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setUser($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getUser() === $this) {
                $image->setUser(null);
            }
        }

        return $this;
    }
}
