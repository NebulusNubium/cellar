<?php

namespace App\Entity;

use App\Repository\RegionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegionsRepository::class)]
class Regions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Bottles>
     */
    #[ORM\OneToMany(targetEntity: Bottles::class, mappedBy: 'regions')]
    private Collection $bottle;

    public function __construct()
    {
        $this->bottle = new ArrayCollection();
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

    /**
     * @return Collection<int, Bottles>
     */
    public function getBottle(): Collection
    {
        return $this->bottle;
    }

    public function addBottle(Bottles $bottle): static
    {
        if (!$this->bottle->contains($bottle)) {
            $this->bottle->add($bottle);
            $bottle->setRegions($this);
        }

        return $this;
    }

    public function removeBottle(Bottles $bottle): static
    {
        if ($this->bottle->removeElement($bottle)) {
            // set the owning side to null (unless already changed)
            if ($bottle->getRegions() === $this) {
                $bottle->setRegions(null);
            }
        }

        return $this;
    }
}
