<?php

namespace App\Economat\Entity;

use App\Facturation\Entity\Facture;
use App\Repository\PaiementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'paiements')]
    private ?Facture $facture = null;

    #[ORM\Column]
    private ?\DateTime $datedepaiement = null;

    #[ORM\Column]
    private ?float $montant = null;

    public function __construct()
    {
        $this->datedepaiement = new \DateTime();
    }

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

    public function getDatedepaiement(): ?\DateTime
    {
        return $this->datedepaiement;
    }

    public function setDatedepaiement(\DateTime $datedepaiement): static
    {
        $this->datedepaiement = $datedepaiement;

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
}
