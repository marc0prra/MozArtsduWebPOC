<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Représente un salarié de l'entreprise.
 * Le PIN est stocké sous forme de hash bcrypt.
 */
#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    /** Code PIN haché avec password_hash() */
    #[ORM\Column(length: 255)]
    private ?string $pinHash = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Un salarié peut avoir plusieurs pointages (arrivées et départs).
     *
     * @var Collection<int, Clocking>
     */
    #[ORM\OneToMany(targetEntity: Clocking::class, mappedBy: 'employee', orphanRemoval: true)]
    private Collection $clockings;

    public function __construct()
    {
        $this->clockings = new ArrayCollection();
    }

    /** Initialise automatiquement la date de création avant le premier enregistrement. */
    #[ORM\PrePersist]
    public function initCreatedAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPinHash(): ?string
    {
        return $this->pinHash;
    }

    public function setPinHash(string $pinHash): static
    {
        $this->pinHash = $pinHash;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /** @return Collection<int, Clocking> */
    public function getClockings(): Collection
    {
        return $this->clockings;
    }

    public function addClocking(Clocking $clocking): static
    {
        if (!$this->clockings->contains($clocking)) {
            $this->clockings->add($clocking);
            $clocking->setEmployee($this);
        }

        return $this;
    }

    public function removeClocking(Clocking $clocking): static
    {
        if ($this->clockings->removeElement($clocking)) {
            if ($clocking->getEmployee() === $this) {
                $clocking->setEmployee(null);
            }
        }

        return $this;
    }
}
