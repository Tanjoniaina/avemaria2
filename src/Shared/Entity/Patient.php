<?php

namespace App\Shared\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
#[Broadcast]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 17)]
    private ?string $numero = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $prenoms = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $adresse = null;

    /**
     * @var Collection<int, Dossierpatient>
     */
    #[ORM\OneToMany(targetEntity: Dossierpatient::class, mappedBy: 'Patient')]
    private Collection $dossierpatients;

    public function __construct()
    {
        $this->dossierpatients = new ArrayCollection();
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenoms(): ?string
    {
        return $this->prenoms;
    }

    public function setPrenoms(?string $prenoms): static
    {
        $this->prenoms = $prenoms;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection<int, Dossierpatient>
     */
    public function getdossierpatients(): Collection
    {
        return $this->dossierpatients;
    }

    public function adddossierpatients(Dossierpatient $dossierpatients): static
    {
        if (!$this->dossierpatients->contains($dossierpatients)) {
            $this->dossierpatients->add($dossierpatients);
            $dossierpatients->setPatient($this);
        }

        return $this;
    }

    public function removedossierpatients(Dossierpatient $dossierpatients): static
    {
        if ($this->dossierpatients->removeElement($dossierpatients)) {
            // set the owning side to null (unless already changed)
            if ($dossierpatients->getPatient() === $this) {
                $dossierpatients->setPatient(null);
            }
        }

        return $this;
    }
}
