<?php

namespace App\Entity;

use App\Repository\InventoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InventoryRepository::class)]
class Inventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'stock')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cellars $cellar = null;

    #[ORM\ManyToOne(inversedBy: 'stock')]
    private ?Bottles $wine = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCellar(): ?Cellars
    {
        return $this->cellar;
    }

    public function setCellar(?Cellars $cellar): static
    {
        $this->cellar = $cellar;

        return $this;
    }

    public function getWine(): ?Bottles
    {
        return $this->wine;
    }

    public function setWine(?Bottles $wine): static
    {
        $this->wine = $wine;

        return $this;
    }
}
