<?php

namespace App\Pharmaciegros\Entity;

use App\Repository\TransferRepository;
use App\Shared\Entity\Location;
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
    private Collection $ligne;

    #[ORM\Column(length: 20)]
    private ?string $type = null;

    public function __construct()
    {
        $this->ligne = new ArrayCollection();
        $this->transfertDate = new \DateTime();
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
    public function getLigne(): Collection
    {
        return $this->ligne;
    }

    public function addLigne(TransferLine $ligne): static
    {
        if (!$this->ligne->contains($ligne)) {
            $this->ligne->add($ligne);
            $ligne->setTransfert($this);
        }

        return $this;
    }

    public function removeLigne(TransferLine $ligne): static
    {
        if ($this->ligne->removeElement($ligne)) {
            // set the owning side to null (unless already changed)
            if ($ligne->getTransfert() === $this) {
                $ligne->setTransfert(null);
            }
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
