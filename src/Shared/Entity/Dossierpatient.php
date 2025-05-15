<?php

namespace App\Shared\Entity;

use App\Facturation\Entity\Facture;
use App\Repository\DossierpatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: DossierpatientRepository::class)]
#[Broadcast]
class Dossierpatient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'typeparcours')]
    private ?Patient $Patient = null;

    #[ORM\Column(length: 50)]
    private ?string $typeparcours = null;

    #[ORM\Column(length: 50)]
    private ?string $etatparcours = null;

    #[ORM\Column]
    private ?\DateTime $datedebut = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $datefin = null;

    /**
     * @var Collection<int, Facture>
     */
    #[ORM\OneToMany(targetEntity: Facture::class, mappedBy: 'Dossierpatient')]
    private Collection $factures;

    public function __construct()
    {
        $this->factures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): ?Patient
    {
        return $this->Patient;
    }

    public function setPatient(?Patient $Patient): static
    {
        $this->Patient = $Patient;

        return $this;
    }

    public function getTypeparcours(): ?string
    {
        return $this->typeparcours;
    }

    public function setTypeparcours(string $typeparcours): static
    {
        $this->typeparcours = $typeparcours;

        return $this;
    }

    public function getEtatparcours(): ?string
    {
        return $this->etatparcours;
    }

    public function setEtatparcours(string $etatparcours): static
    {
        $this->etatparcours = $etatparcours;

        return $this;
    }

    public function getDatedebut(): ?\DateTime
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTime $datedebut): static
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTime
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTime $datefin): static
    {
        $this->datefin = $datefin;

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): static
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setDossierpatient($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getDossierpatient() === $this) {
                $facture->setDossierpatient(null);
            }
        }

        return $this;
    }
}
