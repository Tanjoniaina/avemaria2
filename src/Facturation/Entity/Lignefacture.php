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

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?int $referenceid = null;

    private $tarifacte = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getReferenceid(): ?int
    {
        return $this->referenceid;
    }

    public function setReferenceid(?int $referenceid): static
    {
        $this->referenceid = $referenceid;

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
