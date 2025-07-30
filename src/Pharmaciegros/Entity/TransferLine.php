<?php

namespace App\Pharmaciegros\Entity;

use App\Repository\TransferLineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransferLineRepository::class)]
class TransferLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'transferLines')]
    private ?Transfer $transfert = null;

    #[ORM\ManyToOne(inversedBy: 'transferLines')]
    private ?Product $product = null;

    #[ORM\Column]
    private ?int $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransfert(): ?Transfer
    {
        return $this->transfert;
    }

    public function setTransfert(?Transfer $transfert): static
    {
        $this->transfert = $transfert;

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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }
}
