<?php

namespace App\Entity;

use App\Repository\BottlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BottlesRepository::class)]
class Bottles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column(length: 255)]
    private ?string $grapes = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $publishedAt = null;

    /**
     * @var Collection<int, Cellars>
     */
    #[ORM\ManyToMany(targetEntity: Cellars::class, inversedBy: 'cellar')]
    private Collection $cellar;

    #[ORM\ManyToOne(inversedBy: 'bottle')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Regions $regions = null;

    #[ORM\ManyToOne(inversedBy: 'bottle')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Countries $countries = null;

    public function __construct()
    {
        $this->cellar = new ArrayCollection();
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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getGrapes(): ?string
    {
        return $this->grapes;
    }

    public function setGrapes(string $grapes): static
    {
        $this->grapes = $grapes;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * @return Collection<int, Cellars>
     */
    public function getCellar(): Collection
    {
        return $this->cellar;
    }

    public function addCellar(Cellars $cellar): static
    {
        if (!$this->cellar->contains($cellar)) {
            $this->cellar->add($cellar);
        }

        return $this;
    }

    public function removeCellar(Cellars $cellar): static
    {
        $this->cellar->removeElement($cellar);

        return $this;
    }

    public function getRegions(): ?Regions
    {
        return $this->regions;
    }

    public function setRegions(?Regions $regions): static
    {
        $this->regions = $regions;

        return $this;
    }

    public function getCountries(): ?Countries
    {
        return $this->countries;
    }

    public function setCountries(?Countries $countries): static
    {
        $this->countries = $countries;

        return $this;
    }
}
