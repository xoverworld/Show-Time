<?php

namespace App\Entity;

use App\Repository\UserDetailsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserDetailsRepository::class)]
class UserDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    #[ORM\Column]
    private ?int $age = null;

    /**
     * @var Collection<int, Purchased>
     */
    #[ORM\OneToMany(targetEntity: Purchased::class, mappedBy: 'userDetails')]
    private Collection $purchasedTickets;

    #[ORM\OneToOne(mappedBy: 'userDetails', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function __construct()
    {
        $this->purchasedTickets = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

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
            $purchasedTicket->setUserDetails($this);
        }

        return $this;
    }

    public function removePurchasedTicket(Purchased $purchasedTicket): static
    {
        if ($this->purchasedTickets->removeElement($purchasedTicket)) {
            // set the owning side to null (unless already changed)
            if ($purchasedTicket->getUserDetails() === $this) {
                $purchasedTicket->setUserDetails(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        // set the owning side of the relation if necessary
        if ($user->getUserDetails() !== $this) {
            $user->setUserDetails($this);
        }

        $this->user = $user;

        return $this;
    }
}
