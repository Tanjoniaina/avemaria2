<?php

namespace App\Pharmaciegros\Entity;

use App\Repository\ReceptionlineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReceptionlineRepository::class)]
class Receptionline
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'receptionlines')]
    private ?Reception $reception = null;

    #[ORM\ManyToOne(inversedBy: 'receptionlines')]
    private ?Product $product = null;

    #[ORM\Column]
    private ?int $quantityReceived = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReception(): ?Reception
    {
        return $this->reception;
    }

    public function setReception(?Reception $reception): static
    {
        $this->reception = $reception;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantityReceived(): ?int
    {
        return $this->quantityReceived;
    }

    public function setQuantityReceived(int $quantityReceived): static
    {
        $this->quantityReceived = $quantityReceived;

        return $this;
    }
}
