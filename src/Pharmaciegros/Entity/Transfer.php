<?php

namespace App\Pharmaciegros\Entity;

use App\Repository\TransferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransferRepository::class)]
class Transfer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'transfers')]
    private ?Location $sourceLocation = null;

    #[ORM\ManyToOne(inversedBy: 'transfers')]
    private ?Location $destinationLocation = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTime $transfertDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    /**
     * @var Collection<int, TransferLine>
     */
    #[ORM\OneToMany(targetEntity: TransferLine::class, mappedBy: 'transfert')]
    private Collection $transferLines;

    public function __construct()
    {
        $this->transferLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceLocation(): ?Location
    {
        return $this->sourceLocation;
    }

    public function setSourceLocation(?Location $sourceLocation): static
    {
        $this->sourceLocation = $sourceLocation;

        return $this;
    }

    public function getDestinationLocation(): ?Location
    {
        return $this->destinationLocation;
    }

    public function setDestinationLocation(?Location $destinationLocation): static
    {
        $this->destinationLocation = $destinationLocation;

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

    public function getTransfertDate(): ?\DateTime
    {
        return $this->transfertDate;
    }

    public function setTransfertDate(\DateTime $transfertDate): static
    {
        $this->transfertDate = $transfertDate;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, TransferLine>
     */
    public function getTransferLines(): Collection
    {
        return $this->transferLines;
    }

    public function addTransferLine(TransferLine $transferLine): static
    {
        if (!$this->transferLines->contains($transferLine)) {
            $this->transferLines->add($transferLine);
            $transferLine->setTransfert($this);
        }

        return $this;
    }

    public function removeTransferLine(TransferLine $transferLine): static
    {
        if ($this->transferLines->removeElement($transferLine)) {
            // set the owning side to null (unless already changed)
            if ($transferLine->getTransfert() === $this) {
                $transferLine->setTransfert(null);
            }
        }

        return $this;
    }
}
