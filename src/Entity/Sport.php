<?php

namespace App\Entity;

use App\Repository\SportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SportRepository::class)]
class Sport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'sport', targetEntity: Product::class)]
    private Collection $products;
    #[ORM\Column]
    private ?bool $displayMenu = null;

    #[ORM\ManyToMany(targetEntity: League::class, mappedBy: 'sports')]
    private Collection $leagues;

    #[ORM\Column]
    private ?bool $available = null;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->leagues = new ArrayCollection();
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
            $product->setSport($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getSport() === $this) {
                $product->setSport(null);
            }
        }

        return $this;
    }

    public function isDisplayMenu(): ?bool
    {
        return $this->displayMenu;
    }

    public function setDisplayMenu(bool $displayMenu): static
    {
        $this->displayMenu = $displayMenu;

        return $this;
    }

    /**
     * @return Collection<int, League>
     */
    public function getLeagues(): Collection
    {
        return $this->leagues;
    }

    public function addLeague(League $league): static
    {
        if (!$this->leagues->contains($league)) {
            $this->leagues->add($league);
            $league->addSport($this);
        }

        return $this;
    }

    public function removeLeague(League $league): static
    {
        if ($this->leagues->removeElement($league)) {
            $league->removeSport($this);
        }

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

        return $this;
    }
}
