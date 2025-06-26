<?php

namespace App\Entity;

use App\Repository\PurchasedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchasedRepository::class)]
class Purchased
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'purchasedTickets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserDetails $userDetails = null;

    #[ORM\ManyToOne(inversedBy: 'purchasedTickets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Festival $festival = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserDetails(): ?UserDetails
    {
        return $this->userDetails;
    }

    public function setUserDetails(?UserDetails $userDetails): static
    {
        $this->userDetails = $userDetails;

        return $this;
    }

    public function getFestival(): ?Festival
    {
        return $this->festival;
    }

    public function setFestival(?Festival $festival): static
    {
        $this->festival = $festival;

        return $this;
    }
}
