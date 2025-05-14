<?php

namespace App\Prisedesparametres\Entity;

use App\Repository\ParametreentreRepository;
use App\Shared\Entity\Dossierpatient;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParametreentreRepository::class)]
class Parametreentre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $tensionarteriel = null;

    #[ORM\Column(length: 5)]
    private ?string $temperature = null;

    #[ORM\Column(length: 10)]
    private ?string $fc = null;

    #[ORM\Column(length: 10)]
    private ?string $fr = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $glycemie = null;

    #[ORM\Column(length: 5)]
    private ?string $poids = null;

    #[ORM\Column(length: 5)]
    private ?string $spo2 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $remarque = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Dossierpatient $dossierpatient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTensionarteriel(): ?string
    {
        return $this->tensionarteriel;
    }

    public function setTensionarteriel(string $tensionarteriel): static
    {
        $this->tensionarteriel = $tensionarteriel;

        return $this;
    }

    public function getTemperature(): ?string
    {
        return $this->temperature;
    }

    public function setTemperature(string $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getFc(): ?string
    {
        return $this->fc;
    }

    public function setFc(string $fc): static
    {
        $this->fc = $fc;

        return $this;
    }

    public function getFr(): ?string
    {
        return $this->fr;
    }

    public function setFr(string $fr): static
    {
        $this->fr = $fr;

        return $this;
    }

    public function getGlycemie(): ?string
    {
        return $this->glycemie;
    }

    public function setGlycemie(string $glycemie): static
    {
        $this->glycemie = $glycemie;

        return $this;
    }

    public function getPoids(): ?string
    {
        return $this->poids;
    }

    public function setPoids(string $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getSpo2(): ?string
    {
        return $this->spo2;
    }

    public function setSpo2(string $spo2): static
    {
        $this->spo2 = $spo2;

        return $this;
    }

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(?string $remarque): static
    {
        $this->remarque = $remarque;

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
}
