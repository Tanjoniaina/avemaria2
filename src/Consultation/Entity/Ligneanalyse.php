<?php

namespace App\Consultation\Entity;

use App\Facturation\Entity\Typeanalyse;
use App\Repository\LigneanalyseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneanalyseRepository::class)]
class Ligneanalyse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ligneanalyses')]
    private ?Ordonnance $ordonnance = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\ManyToOne(inversedBy: 'ligneanalyses')]
    private ?Typeanalyse $typeanalyse = null;

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

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getTypeanalyse(): ?Typeanalyse
    {
        return $this->typeanalyse;
    }

    public function setTypeanalyse(?Typeanalyse $typeanalyse): static
    {
        $this->typeanalyse = $typeanalyse;

        return $this;
    }
}
