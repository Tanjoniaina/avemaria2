<?php

namespace App\Pharmaciegros\Entity;

use App\Repository\PurchaseorderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseorderRepository::class)]
class Purchaseorder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'purchaseorders')]
    private ?Supplier $supplier = null;

    #[ORM\Column]
    private ?\DateTime $orderdate = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $referencenumber = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalamount = null;

    /**
     * @var Collection<int, Purchaseorderline>
     */
    #[ORM\OneToMany(targetEntity: Purchaseorderline::class, mappedBy: 'purchaseorder')]
    private Collection $purchaseorderlines;

    public function __construct()
    {
        $this->purchaseorderlines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): static
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getOrderdate(): ?\DateTime
    {
        return $this->orderdate;
    }

    public function setOrderdate(\DateTime $orderdate): static
    {
        $this->orderdate = $orderdate;

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

    public function getReferencenumber(): ?string
    {
        return $this->referencenumber;
    }

    public function setReferencenumber(?string $referencenumber): static
    {
        $this->referencenumber = $referencenumber;

        return $this;
    }

    public function getTotalamount(): ?float
    {
        return $this->totalamount;
    }

    public function setTotalamount(?float $totalamount): static
    {
        $this->totalamount = $totalamount;

        return $this;
    }

    /**
     * @return Collection<int, Purchaseorderline>
     */
    public function getPurchaseorderlines(): Collection
    {
        return $this->purchaseorderlines;
    }

    public function addPurchaseorderline(Purchaseorderline $purchaseorderline): static
    {
        if (!$this->purchaseorderlines->contains($purchaseorderline)) {
            $this->purchaseorderlines->add($purchaseorderline);
            $purchaseorderline->setPurchaseorder($this);
        }

        return $this;
    }

    public function removePurchaseorderline(Purchaseorderline $purchaseorderline): static
    {
        if ($this->purchaseorderlines->removeElement($purchaseorderline)) {
            // set the owning side to null (unless already changed)
            if ($purchaseorderline->getPurchaseorder() === $this) {
                $purchaseorderline->setPurchaseorder(null);
            }
        }

        return $this;
    }
}
