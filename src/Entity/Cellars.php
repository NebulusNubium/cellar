<?php

namespace App\Entity;
use App\Entity\Bottles;
use App\Repository\CellarsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CellarsRepository::class)]
class Cellars
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(inversedBy: 'cellar', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $publishedAt = null;

    /**
     * @var Collection<int, Bottles>
     */
    #[ORM\ManyToMany(targetEntity: Bottles::class, inversedBy: 'cellar')]
    #[ORM\JoinTable(name: 'cellar_bottles')]
    private Collection $wines;

    /**
     * @var Collection<int, Inventory>
     */
    #[ORM\OneToMany(targetEntity: Inventory::class, mappedBy: 'cellar')]
    private Collection $stock;

    public function __construct()
    {
        $this->wines = new ArrayCollection();
        $this->stock = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = \DateTimeImmutable::createFromInterface($publishedAt);
        return $this;
    }

    /**
     * @return Collection<int, Bottles>
     */
    public function getWines(): Collection
    {
        return $this->wines;
    }

    public function addWine(Bottles $wine): static
    {
        if (!$this->wines->contains($wine)) {
            $this->wines->add($wine);
        }

        return $this;
    }

    public function removeWine(Bottles $wine): static
    {
        $this->wines->removeElement($wine);

        return $this;
    }

    /**
     * @return Collection<int, Inventory>
     */
    public function getStock(): Collection
    {
        return $this->stock;
    }

    public function addStock(Inventory $stock): static
    {
        if (!$this->stock->contains($stock)) {
            $this->stock->add($stock);
            $stock->setCellar($this);
        }

        return $this;
    }

    public function removeStock(Inventory $stock): static
    {
        if ($this->stock->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getCellar() === $this) {
                $stock->setCellar(null);
            }
        }

        return $this;
    }

}
