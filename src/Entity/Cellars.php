<?php

namespace App\Entity;

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
    #[ORM\ManyToMany(targetEntity: Bottles::class, mappedBy: 'cellar')]
    private Collection $cellar;

    /**
     * @var Collection<int, Bottles>
     */
    #[ORM\ManyToMany(targetEntity: Bottles::class, inversedBy: 'cellars')]
    private Collection $wines;

    public function __construct()
    {
        $this->cellar = new ArrayCollection();
        $this->wines = new ArrayCollection();
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
    public function getCellar(): Collection
    {
        return $this->cellar;
    }

    public function addCellar(Bottles $cellar): static
    {
        if (!$this->cellar->contains($cellar)) {
            $this->cellar->add($cellar);
            $cellar->addCellar($this);
        }

        return $this;
    }

    public function removeCellar(Bottles $cellar): static
    {
        if ($this->cellar->removeElement($cellar)) {
            $cellar->removeCellar($this);
        }

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
}
