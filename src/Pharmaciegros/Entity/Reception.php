<?php

namespace App\Pharmaciegros\Entity;

use App\Repository\ReceptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, Receptionline>
     */
    #[ORM\OneToMany(targetEntity: Receptionline::class, mappedBy: 'reception')]
    private Collection $ligne;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Invoice $invoice = null;

    public function __construct()
    {
        $this->ligne = new ArrayCollection();
    }

    public function getLigne(): Collection
    {
        return $this->ligne;
    }

    public function addLigne(Receptionline $ligne): static
    {
        if (!$this->ligne->contains($ligne)) {
            $this->ligne->add($ligne);
            $ligne->setReception($this);
        }

        return $this;
    }

    public function removeLigne(Receptionline $ligne): static
    {
        if ($this->ligne->removeElement($ligne)) {
            // set the owning side to null (unless already changed)
            if ($ligne->getReception() === $this) {
                $ligne->setReception(null);
            }
        }

        return $this;
    }

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

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): static
    {
        $this->invoice = $invoice;

        return $this;
    }

}
