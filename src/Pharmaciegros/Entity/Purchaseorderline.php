<?php

namespace App\Pharmaciegros\Entity;

use App\Repository\PurchaseorderlineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseorderlineRepository::class)]
class Purchaseorderline
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'purchaseorderlines')]
    private ?Purchaseorder $purchaseorder = null;

    #[ORM\ManyToOne(inversedBy: 'quantityordered')]
    private ?Product $product = null;

    #[ORM\Column]
    private ?int $quantityordered = null;

    #[ORM\Column]
    private ?float $unitprice = null;

    #[ORM\Column]
    private ?float $subtotal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPurchaseorder(): ?Purchaseorder
    {
        return $this->purchaseorder;
    }

    public function setPurchaseorder(?Purchaseorder $purchaseorder): static
    {
        $this->purchaseorder = $purchaseorder;

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

    public function getQuantityordered(): ?int
    {
        return $this->quantityordered;
    }

    public function setQuantityordered(int $quantityordered): static
    {
        $this->quantityordered = $quantityordered;

        return $this;
    }

    public function getUnitprice(): ?float
    {
        return $this->unitprice;
    }

    public function setUnitprice(float $unitprice): static
    {
        $this->unitprice = $unitprice;

        return $this;
    }

    public function getSubtotal(): ?float
    {
        return $this->subtotal;
    }

    public function setSubtotal(float $subtotal): static
    {
        $this->subtotal = $subtotal;

        return $this;
    }
}
