<?php

namespace App\Shared\Entity;

use App\Facturation\Entity\Lignefacture;
use App\Repository\TarifacteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TarifacteRepository::class)]
class Tarifacte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?float $montant = null;

    /**
     * @var Collection<int, Lignefacture>
     */
    #[ORM\OneToMany(targetEntity: Lignefacture::class, mappedBy: 'tarifacte')]
    private Collection $lignefactures;

    public function __construct()
    {
        $this->lignefactures = new ArrayCollection();
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
     * @return Collection<int, Lignefacture>
     */
    public function getLignefactures(): Collection
    {
        return $this->lignefactures;
    }

    public function addLignefacture(Lignefacture $lignefacture): static
    {
        if (!$this->lignefactures->contains($lignefacture)) {
            $this->lignefactures->add($lignefacture);
            $lignefacture->setTarifacte($this);
        }

        return $this;
    }

    public function removeLignefacture(Lignefacture $lignefacture): static
    {
        if ($this->lignefactures->removeElement($lignefacture)) {
            // set the owning side to null (unless already changed)
            if ($lignefacture->getTarifacte() === $this) {
                $lignefacture->setTarifacte(null);
            }
        }

        return $this;
    }
}
