<?php

namespace App\Facturation\Entity;

use App\Economat\Entity\Paiement;
use App\Repository\FactureRepository;
use App\Shared\Entity\Dossierpatient;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?Dossierpatient $Dossierpatient = null;

    #[ORM\Column]
    private ?\DateTime $datefacture = null;

    #[ORM\Column]
    private ?bool $estpaye = false;

    /**
     * @var Collection<int, Lignefacture>
     */
    #[ORM\OneToMany(targetEntity: Lignefacture::class, mappedBy: 'facture')]
    private Collection $ligne;

    /**
     * @var Collection<int, Paiement>
     */
    #[ORM\OneToMany(targetEntity: Paiement::class, mappedBy: 'facture')]
    private Collection $paiements;

    #[ORM\Column(length: 20)]
    private ?string $numero = null;

    public function __construct()
    {
        $this->ligne = new ArrayCollection();
        $this->paiements = new ArrayCollection();
        $this->datefacture = new \DateTime();
    }

    public function getLigne(): Collection
    {
        return $this->ligne;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDossierpatient(): ?Dossierpatient
    {
        return $this->Dossierpatient;
    }

    public function setDossierpatient(?Dossierpatient $Dossierpatient): static
    {
        $this->Dossierpatient = $Dossierpatient;

        return $this;
    }

    public function getDatefacture(): ?\DateTime
    {
        return $this->datefacture;
    }

    public function setDatefacture(\DateTime $datefacture): static
    {
        $this->datefacture = $datefacture;

        return $this;
    }

    public function isEstpaye(): ?bool
    {
        return $this->estpaye;
    }

    public function setEstpaye(bool $estpaye): static
    {
        $this->estpaye = $estpaye;

        return $this;
    }


    public function addLigne(Lignefacture $ligne): static
    {
        if (!$this->ligne->contains($ligne)) {
            $this->ligne->add($ligne);
            $ligne->setFacture($this);
        }

        return $this;
    }

    public function removeLigne(Lignefacture $ligne): static
    {
        if ($this->ligne->removeElement($ligne)) {
            // set the owning side to null (unless already changed)
            if ($ligne->getFacture() === $this) {
                $ligne->setFacture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Paiement>
     */
    public function getPaiements(): Collection
    {
        return $this->paiements;
    }

    public function addPaiement(Paiement $paiement): static
    {
        if (!$this->paiements->contains($paiement)) {
            $this->paiements->add($paiement);
            $paiement->setFacture($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): static
    {
        if ($this->paiements->removeElement($paiement)) {
            // set the owning side to null (unless already changed)
            if ($paiement->getFacture() === $this) {
                $paiement->setFacture(null);
            }
        }

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getTotal(): float
    {
        return array_reduce(
            $this->ligne->toArray(),
            fn (float $total, LigneFacture $li) => $total + $li->getMontant(),
            0.0
        );
    }

    public function getTotalPaye(): float
    {
        return array_reduce(
            $this->paiements->toArray(),
            fn (float $total, Paiement $p) => $total + $p->getMontant(),
            0.0
        );
    }

    public function getResteAPayer(): float
    {
        return max(0, $this->getTotal() - $this->getTotalPaye());
    }

    public function isEntierementPayee(): bool
    {
        return $this->getResteAPayer() <= 0;
    }

}
