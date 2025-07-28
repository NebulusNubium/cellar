<?php

namespace App\Entity;

use DateTimeImmutable;
use App\Entity\Cellars;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BottlesRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[Vich\Uploadable]
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
    #[ORM\ManyToMany(targetEntity: Cellars::class, inversedBy: 'wines')]
    private Collection $cellar;

    #[ORM\ManyToOne(inversedBy: 'bottle')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Regions $regions = null;

    #[ORM\ManyToOne(inversedBy: 'bottle')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Countries $countries = null;

    /**
     * @var Collection<int, Cellars>
     */
    #[ORM\ManyToMany(targetEntity: Cellars::class, mappedBy: 'wines')]
    private Collection $cellars;

    #[Vich\UploadableField(mapping: 'images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'bottle')]
    private Collection $users;

    /**
     * @var Collection<int, Inventory>
     */
    #[ORM\OneToMany(targetEntity: Inventory::class, mappedBy: 'wine')]
    private Collection $stock;
/**
 * @param File|null $imageFile
 */
public function setImageFile(?File $imageFile = null): void
{
    $this->imageFile = $imageFile;
}

public function getImageFile(): ?File
{
    return $this->imageFile;
}


    public function __construct()
    {
        $this->cellar = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function setPublishedAt(\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = \DateTimeImmutable::createFromInterface($publishedAt);
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

    /**
     * @return Collection<int, Cellars>
     */
    public function getCellars(): Collection
    {
        return $this->cellars;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addBottle($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeBottle($this);
        }

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
            $stock->setWine($this);
        }

        return $this;
    }

    public function removeStock(Inventory $stock): static
    {
        if ($this->stock->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getWine() === $this) {
                $stock->setWine(null);
            }
        }

        return $this;
    }

}
