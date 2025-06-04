<?php

namespace App\Pharmaciegros\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    private ?Supplier $supplier = null;

    #[ORM\Column(length: 20)]
    private ?string $invoicenumber = null;

    #[ORM\Column]
    private ?\DateTime $invoicedate = null;

    #[ORM\Column]
    private ?\DateTime $duedate = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    /**
     * @var Collection<int, Payment>
     */
    #[ORM\OneToMany(targetEntity: Payment::class, mappedBy: 'invoice')]
    private Collection $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
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

    public function getInvoicenumber(): ?string
    {
        return $this->invoicenumber;
    }

    public function setInvoicenumber(string $invoicenumber): static
    {
        $this->invoicenumber = $invoicenumber;

        return $this;
    }

    public function getInvoicedate(): ?\DateTime
    {
        return $this->invoicedate;
    }

    public function setInvoicedate(\DateTime $invoicedate): static
    {
        $this->invoicedate = $invoicedate;

        return $this;
    }

    public function getDuedate(): ?\DateTime
    {
        return $this->duedate;
    }

    public function setDuedate(\DateTime $duedate): static
    {
        $this->duedate = $duedate;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

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

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setInvoice($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getInvoice() === $this) {
                $payment->setInvoice(null);
            }
        }

        return $this;
    }
}
