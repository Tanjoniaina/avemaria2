<?php

namespace App\Facturation\Entity;

use App\Repository\LignefactureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LignefactureRepository::class)]
class Lignefacture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lignefactures')]
    private ?Facture $facture = null;


    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\ManyToOne(inversedBy: 'lignefactures')]
    private ?Tarifacte $tarifacte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): static
    {
        $this->facture = $facture;

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

    public function getTarifacte(): ?Tarifacte
    {
        return $this->tarifacte;
    }

    public function setTarifacte(?Tarifacte $tarifacte): static
    {
        $this->tarifacte = $tarifacte;

        return $this;
    }
}
