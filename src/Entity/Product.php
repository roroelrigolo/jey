<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Sport $sport = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Team $team = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Brand $brand = null;

    #[ORM\Column(length: 5)]
    private ?string $size = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $conditionnement = null;

    #[ORM\Column(length: 255)]
    private ?string $statement = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?League $league = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Player $player = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Image::class)]
    private Collection $images;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;

    #[ORM\Column(nullable: true)]
    private ?int $number = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductLike::class)]
    private Collection $productLikes;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductView::class)]
    private Collection $productViews;

    #[ORM\ManyToMany(targetEntity: Color::class, inversedBy: 'products')]
    private Collection $colors;

    #[ORM\Column]
    private ?bool $flock = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Alert::class)]
    private Collection $alerts;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Conversation::class)]
    private Collection $conversations;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Notification::class)]
    private Collection $notifications;

    #[ORM\ManyToOne]
    private ?User $buyer = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: CancelBook::class)]
    private Collection $cancelBooks;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Assessment::class)]
    private Collection $assessments;

    #[ORM\ManyToMany(targetEntity: Textil::class, inversedBy: 'products')]
    private Collection $textils;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->productLikes = new ArrayCollection();
        $this->productViews = new ArrayCollection();
        $this->colors = new ArrayCollection();
        $this->alerts = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->cancelBooks = new ArrayCollection();
        $this->assessments = new ArrayCollection();
        $this->textils = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSport(): ?Sport
    {
        return $this->sport;
    }

    public function setSport(?Sport $sport): static
    {
        $this->sport = $sport;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getConditionnement(): ?string
    {
        return $this->conditionnement;
    }

    public function setConditionnement(string $conditionnement): static
    {
        $this->conditionnement = $conditionnement;

        return $this;
    }

    public function getStatement(): ?string
    {
        return $this->statement;
    }

    public function setStatement(string $statement): static
    {
        $this->statement = $statement;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getLeague(): ?League
    {
        return $this->league;
    }

    public function setLeague(?League $league): static
    {
        $this->league = $league;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

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
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

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
            $productLike->setProduct($this);
        }

        return $this;
    }

    public function removeProductLike(ProductLike $productLike): static
    {
        if ($this->productLikes->removeElement($productLike)) {
            // set the owning side to null (unless already changed)
            if ($productLike->getProduct() === $this) {
                $productLike->setProduct(null);
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
            $productView->setProduct($this);
        }

        return $this;
    }

    public function removeProductView(ProductView $productView): static
    {
        if ($this->productViews->removeElement($productView)) {
            // set the owning side to null (unless already changed)
            if ($productView->getProduct() === $this) {
                $productView->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Color>
     */
    public function getColors(): Collection
    {
        return $this->colors;
    }

    public function addColor(Color $color): static
    {
        if (!$this->colors->contains($color)) {
            $this->colors->add($color);
        }

        return $this;
    }

    public function removeColor(Color $color): static
    {
        $this->colors->removeElement($color);

        return $this;
    }

    public function isFlock(): ?bool
    {
        return $this->flock;
    }

    public function setFlock(bool $flock): static
    {
        $this->flock = $flock;

        return $this;
    }

    /**
     * @return Collection<int, Alert>
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    public function addAlert(Alert $alert): static
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts->add($alert);
            $alert->setProduct($this);
        }

        return $this;
    }

    public function removeAlert(Alert $alert): static
    {
        if ($this->alerts->removeElement($alert)) {
            // set the owning side to null (unless already changed)
            if ($alert->getProduct() === $this) {
                $alert->setProduct(null);
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
            $conversation->setProduct($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): static
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getProduct() === $this) {
                $conversation->setProduct(null);
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
            $notification->setProduct($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getProduct() === $this) {
                $notification->setProduct(null);
            }
        }

        return $this;
    }

    public function getBuyer(): ?User
    {
        return $this->buyer;
    }

    public function setBuyer(?User $buyer): static
    {
        $this->buyer = $buyer;

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
            $cancelBook->setProduct($this);
        }

        return $this;
    }

    public function removeCancelBook(CancelBook $cancelBook): static
    {
        if ($this->cancelBooks->removeElement($cancelBook)) {
            // set the owning side to null (unless already changed)
            if ($cancelBook->getProduct() === $this) {
                $cancelBook->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Assessment>
     */
    public function getAssessments(): Collection
    {
        return $this->assessments;
    }

    public function addAssessment(Assessment $assessment): static
    {
        if (!$this->assessments->contains($assessment)) {
            $this->assessments->add($assessment);
            $assessment->setProduct($this);
        }

        return $this;
    }

    public function removeAssessment(Assessment $assessment): static
    {
        if ($this->assessments->removeElement($assessment)) {
            // set the owning side to null (unless already changed)
            if ($assessment->getProduct() === $this) {
                $assessment->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Textil>
     */
    public function getTextils(): Collection
    {
        return $this->textils;
    }

    public function addTextil(Textil $textil): static
    {
        if (!$this->textils->contains($textil)) {
            $this->textils->add($textil);
        }

        return $this;
    }

    public function removeTextil(Textil $textil): static
    {
        $this->textils->removeElement($textil);

        return $this;
    }
}
