<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ClockingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Représente un pointage (arrivée ou départ) d'un salarié.
 * Le type est soit 'in' (arrivée) soit 'out' (départ).
 */
#[ORM\Entity(repositoryClass: ClockingRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Clocking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** Salarié qui a effectué ce pointage. */
    #[ORM\ManyToOne(inversedBy: 'clockings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employee $employee = null;

    /** Type de pointage */
    #[ORM\Column(length: 10)]
    private ?string $type = null;

    /** Date et heure exactes du pointage, définies automatiquement à la création. */
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /** Initialise automatiquement l'horodatage avant le premier enregistrement. */
    #[ORM\PrePersist]
    public function initCreatedAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
}
