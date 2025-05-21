<?php

namespace App\Pharmaciegros\Entity;

use App\Consultation\Entity\Ligneordonnance;
use App\Repository\MedicamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedicamentRepository::class)]
class Medicament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, Ligneordonnance>
     */
    #[ORM\OneToMany(targetEntity: Ligneordonnance::class, mappedBy: 'medicament')]
    private Collection $ligneordonnances;

    #[ORM\Column]
    private ?float $montant = null;

    public function __construct()
    {
        $this->ligneordonnances = new ArrayCollection();
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

    /**
     * @return Collection<int, Ligneordonnance>
     */
    public function getLigneordonnances(): Collection
    {
        return $this->ligneordonnances;
    }

    public function addLigneordonnance(Ligneordonnance $ligneordonnance): static
    {
        if (!$this->ligneordonnances->contains($ligneordonnance)) {
            $this->ligneordonnances->add($ligneordonnance);
            $ligneordonnance->setMedicament($this);
        }

        return $this;
    }

    public function removeLigneordonnance(Ligneordonnance $ligneordonnance): static
    {
        if ($this->ligneordonnances->removeElement($ligneordonnance)) {
            // set the owning side to null (unless already changed)
            if ($ligneordonnance->getMedicament() === $this) {
                $ligneordonnance->setMedicament(null);
            }
        }

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
