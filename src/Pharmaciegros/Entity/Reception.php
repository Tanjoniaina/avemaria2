<?php

namespace App\Pharmaciegros\Entity;

use App\Repository\ReceptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReceptionRepository::class)]
class Reception
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Purchaseorder $purchaseorder = null;

    #[ORM\Column]
    private ?\DateTime $receiveddate = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

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

    public function getReceiveddate(): ?\DateTime
    {
        return $this->receiveddate;
    }

    public function setReceiveddate(\DateTime $receiveddate): static
    {
        $this->receiveddate = $receiveddate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
