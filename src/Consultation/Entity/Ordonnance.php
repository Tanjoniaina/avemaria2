<?php

namespace App\Consultation\Entity;

use App\Repository\OrdonnanceRepository;
use App\Shared\Entity\Dossierpatient;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdonnanceRepository::class)]
class Ordonnance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $numero = null;

    #[ORM\Column]
    private ?\DateTime $date = null;

    #[ORM\ManyToOne(inversedBy: 'ordonnances')]
    private ?Dossierpatient $dossierpatient = null;

    /**
     * @var Collection<int, Ligneordonnance>
     */
    #[ORM\OneToMany(targetEntity: Ligneordonnance::class, mappedBy: 'ordonnance')]
    private Collection $ligne;

    /**
     * @var Collection<int, Ligneanalyse>
     */
    #[ORM\OneToMany(targetEntity: Ligneanalyse::class, mappedBy: 'ordonnance')]
    private Collection $ligneanalyse;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->ligne = new ArrayCollection();
        $this->ligneanalyse = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDossierpatient(): ?Dossierpatient
    {
        return $this->dossierpatient;
    }

    public function setDossierpatient(?Dossierpatient $dossierpatient): static
    {
        $this->dossierpatient = $dossierpatient;

        return $this;
    }

    /**
     * @return Collection<int, Ligneordonnance>
     */
    public function getLigne(): Collection
    {
        return $this->ligne;
    }

    public function addLigne(Ligneordonnance $ligne): static
    {
        if (!$this->ligne->contains($ligne)) {
            $this->ligne->add($ligne);
            $ligne->setOrdonnance($this);
        }

        return $this;
    }

    public function removeLigneordonnance(Ligneordonnance $ligne): static
    {
        if ($this->ligne->removeElement($ligne)) {
            // set the owning side to null (unless already changed)
            if ($ligne->getOrdonnance() === $this) {
                $ligne->setOrdonnance(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ligneanalyse>
     */
    public function getLigneanalyse(): Collection
    {
        return $this->ligneanalyse;
    }

    public function addLigneanalyse(Ligneanalyse $ligneanalyse): static
    {
        if (!$this->ligneanalyse->contains($ligneanalyse)) {
            $this->ligneanalyse->add($ligneanalyse);
            $ligneanalyse->setOrdonnance($this);
        }

        return $this;
    }

    public function removeLigneanalyse(Ligneanalyse $ligneanalyse): static
    {
        if ($this->ligneanalyse->removeElement($ligneanalyse)) {
            // set the owning side to null (unless already changed)
            if ($ligneanalyse->getOrdonnance() === $this) {
                $ligneanalyse->setOrdonnance(null);
            }
        }

        return $this;
    }
}
