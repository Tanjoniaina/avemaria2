<?php

namespace App\Facturation\Entity;

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

    public function __construct()
    {
        $this->ligne = new ArrayCollection();
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


}
