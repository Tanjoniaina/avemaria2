<?php

namespace App\Facturation\Entity;

use App\Consultation\Entity\Ligneanalyse;
use App\Repository\TypeanalyseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeanalyseRepository::class)]
class Typeanalyse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?float $montant = null;

    /**
     * @var Collection<int, Ligneanalyse>
     */
    #[ORM\OneToMany(targetEntity: Ligneanalyse::class, mappedBy: 'typeanalyse')]
    private Collection $ligneanalyses;

    public function __construct()
    {
        $this->ligneanalyses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    /**
     * @return Collection<int, Ligneanalyse>
     */
    public function getLigneanalyses(): Collection
    {
        return $this->ligneanalyses;
    }

    public function addLigneanalysis(Ligneanalyse $ligneanalysis): static
    {
        if (!$this->ligneanalyses->contains($ligneanalysis)) {
            $this->ligneanalyses->add($ligneanalysis);
            $ligneanalysis->setTypeanalyse($this);
        }

        return $this;
    }

    public function removeLigneanalysis(Ligneanalyse $ligneanalysis): static
    {
        if ($this->ligneanalyses->removeElement($ligneanalysis)) {
            // set the owning side to null (unless already changed)
            if ($ligneanalysis->getTypeanalyse() === $this) {
                $ligneanalysis->setTypeanalyse(null);
            }
        }

        return $this;
    }
}
