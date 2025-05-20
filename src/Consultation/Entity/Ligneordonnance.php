<?php

namespace App\Consultation\Entity;

use App\Pharmaciegros\Entity\Medicament;
use App\Repository\LigneordonnanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneordonnanceRepository::class)]
class Ligneordonnance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ligneordonnances')]
    private ?Ordonnance $ordonnance = null;

    #[ORM\Column(length: 20, nullable: false)]
    private ?string $quantite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $posologie = null;

    #[ORM\ManyToOne(inversedBy: 'ligneordonnances')]
    private ?Medicament $medicament = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdonnance(): ?Ordonnance
    {
        return $this->ordonnance;
    }

    public function setOrdonnance(?Ordonnance $ordonnance): static
    {
        $this->ordonnance = $ordonnance;

        return $this;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(string $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPosologie(): ?string
    {
        return $this->posologie;
    }

    public function setPosologie(?string $posologie): static
    {
        $this->posologie = $posologie;

        return $this;
    }

    public function getMedicament(): ?Medicament
    {
        return $this->medicament;
    }

    public function setMedicament(?Medicament $medicament): static
    {
        $this->medicament = $medicament;

        return $this;
    }
}
