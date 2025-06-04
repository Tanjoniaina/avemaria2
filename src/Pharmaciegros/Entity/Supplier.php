<?php

namespace App\Pharmaciegros\Entity;

use App\Repository\SupplierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SupplierRepository::class)]
class Supplier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(length: 100)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isactive = null;

    /**
     * @var Collection<int, Purchaseorder>
     */
    #[ORM\OneToMany(targetEntity: Purchaseorder::class, mappedBy: 'supplier')]
    private Collection $purchaseorders;

    /**
     * @var Collection<int, Invoice>
     */
    #[ORM\OneToMany(targetEntity: Invoice::class, mappedBy: 'supplier')]
    private Collection $invoices;

    public function __construct()
    {
        $this->purchaseorders = new ArrayCollection();
        $this->invoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function isactive(): ?bool
    {
        return $this->isactive;
    }

    public function setIsactive(?bool $isactive): static
    {
        $this->isactive = $isactive;

        return $this;
    }

    /**
     * @return Collection<int, Purchaseorder>
     */
    public function getPurchaseorders(): Collection
    {
        return $this->purchaseorders;
    }

    public function addPurchaseorder(Purchaseorder $purchaseorder): static
    {
        if (!$this->purchaseorders->contains($purchaseorder)) {
            $this->purchaseorders->add($purchaseorder);
            $purchaseorder->setSupplier($this);
        }

        return $this;
    }

    public function removePurchaseorder(Purchaseorder $purchaseorder): static
    {
        if ($this->purchaseorders->removeElement($purchaseorder)) {
            // set the owning side to null (unless already changed)
            if ($purchaseorder->getSupplier() === $this) {
                $purchaseorder->setSupplier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invoice>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): static
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setSupplier($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): static
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getSupplier() === $this) {
                $invoice->setSupplier(null);
            }
        }

        return $this;
    }
}
