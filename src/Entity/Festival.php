<?php

namespace App\Entity;

use App\Repository\FestivalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FestivalRepository::class)]
class Festival
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $end_date = null;

    /**
     * @var Collection<int, FestivalArtist>
     */
    #[ORM\OneToMany(targetEntity: FestivalArtist::class, mappedBy: 'festival')]
    private Collection $festivalArtists;

    /**
     * @var Collection<int, Purchased>
     */
    #[ORM\OneToMany(targetEntity: Purchased::class, mappedBy: 'festival')]
    private Collection $purchasedTickets;

    public function __construct()
    {
        $this->festivalArtists = new ArrayCollection();
        $this->purchasedTickets = new ArrayCollection();
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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStartDate(): string
    {
        return $this->start_date->format('Y-m-d');
    }

    public function setStartDate(\DateTime $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): string
    {
        return $this->end_date->format('Y-m-d');
    }

    public function setEndDate(\DateTime $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    /**
     * @return Collection<int, FestivalArtist>
     */
    public function getFestivalArtists(): Collection
    {
        return $this->festivalArtists;
    }

    public function addFestivalArtist(FestivalArtist $festivalArtist): static
    {
        if (!$this->festivalArtists->contains($festivalArtist)) {
            $this->festivalArtists->add($festivalArtist);
            $festivalArtist->setFestival($this);
        }

        return $this;
    }

    public function removeFestivalArtist(FestivalArtist $festivalArtist): static
    {
        if ($this->festivalArtists->removeElement($festivalArtist)) {
            // set the owning side to null (unless already changed)
            if ($festivalArtist->getFestival() === $this) {
                $festivalArtist->setFestival(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Purchased>
     */
    public function getPurchasedTickets(): Collection
    {
        return $this->purchasedTickets;
    }

    public function addPurchasedTicket(Purchased $purchasedTicket): static
    {
        if (!$this->purchasedTickets->contains($purchasedTicket)) {
            $this->purchasedTickets->add($purchasedTicket);
            $purchasedTicket->setFestival($this);
        }

        return $this;
    }

    public function removePurchasedTicket(Purchased $purchasedTicket): static
    {
        if ($this->purchasedTickets->removeElement($purchasedTicket)) {
            // set the owning side to null (unless already changed)
            if ($purchasedTicket->getFestival() === $this) {
                $purchasedTicket->setFestival(null);
            }
        }

        return $this;
    }
}
