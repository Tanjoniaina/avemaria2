<?php

namespace App\Pharmaciegros\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 20)]
    private ?string $unit = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $dosage = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $form = null;

    #[ORM\Column]
    private ?float $purchaseprice = null;

    #[ORM\Column]
    private ?float $saleprice = null;

    #[ORM\Column(nullable: true)]
    private ?int $stockmin = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isactive = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Category $category = null;

    /**
     * @var Collection<int, Purchaseorderline>
     */
    #[ORM\OneToMany(targetEntity: Purchaseorderline::class, mappedBy: 'product')]
    private Collection $quantityordered;

    /**
     * @var Collection<int, Dispensation>
     */
    #[ORM\OneToMany(targetEntity: Dispensation::class, mappedBy: 'product')]
    private Collection $dispensations;

    /**
     * @var Collection<int, Stockmovement>
     */
    #[ORM\OneToMany(targetEntity: Stockmovement::class, mappedBy: 'product')]
    private Collection $stockmovements;

    /**
     * @var Collection<int, Receptionline>
     */
    #[ORM\OneToMany(targetEntity: Receptionline::class, mappedBy: 'product')]
    private Collection $receptionlines;

    public function __construct()
    {
        $this->quantityordered = new ArrayCollection();
        $this->dispensations = new ArrayCollection();
        $this->stockmovements = new ArrayCollection();
        $this->receptionlines = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function getDosage(): ?string
    {
        return $this->dosage;
    }

    public function setDosage(?string $dosage): static
    {
        $this->dosage = $dosage;

        return $this;
    }

    public function getForm(): ?string
    {
        return $this->form;
    }

    public function setForm(?string $form): static
    {
        $this->form = $form;

        return $this;
    }

    public function getPurchaseprice(): ?float
    {
        return $this->purchaseprice;
    }

    public function setPurchaseprice(float $purchaseprice): static
    {
        $this->purchaseprice = $purchaseprice;

        return $this;
    }

    public function getSaleprice(): ?float
    {
        return $this->saleprice;
    }

    public function setSaleprice(float $saleprice): static
    {
        $this->saleprice = $saleprice;

        return $this;
    }

    public function getStockmin(): ?int
    {
        return $this->stockmin;
    }

    public function setStockmin(?int $stockmin): static
    {
        $this->stockmin = $stockmin;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Purchaseorderline>
     */
    public function getQuantityordered(): Collection
    {
        return $this->quantityordered;
    }

    public function addQuantityordered(Purchaseorderline $quantityordered): static
    {
        if (!$this->quantityordered->contains($quantityordered)) {
            $this->quantityordered->add($quantityordered);
            $quantityordered->setProduct($this);
        }

        return $this;
    }

    public function removeQuantityordered(Purchaseorderline $quantityordered): static
    {
        if ($this->quantityordered->removeElement($quantityordered)) {
            // set the owning side to null (unless already changed)
            if ($quantityordered->getProduct() === $this) {
                $quantityordered->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Dispensation>
     */
    public function getDispensations(): Collection
    {
        return $this->dispensations;
    }

    public function addDispensation(Dispensation $dispensation): static
    {
        if (!$this->dispensations->contains($dispensation)) {
            $this->dispensations->add($dispensation);
            $dispensation->setProduct($this);
        }

        return $this;
    }

    public function removeDispensation(Dispensation $dispensation): static
    {
        if ($this->dispensations->removeElement($dispensation)) {
            // set the owning side to null (unless already changed)
            if ($dispensation->getProduct() === $this) {
                $dispensation->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Stockmovement>
     */
    public function getStockmovements(): Collection
    {
        return $this->stockmovements;
    }

    public function addStockmovement(Stockmovement $stockmovement): static
    {
        if (!$this->stockmovements->contains($stockmovement)) {
            $this->stockmovements->add($stockmovement);
            $stockmovement->setProduct($this);
        }

        return $this;
    }

    public function removeStockmovement(Stockmovement $stockmovement): static
    {
        if ($this->stockmovements->removeElement($stockmovement)) {
            // set the owning side to null (unless already changed)
            if ($stockmovement->getProduct() === $this) {
                $stockmovement->setProduct(null);
            }
        }

        return $this;
    }

    public function getCurrentStock(): int
    {
        $stock = 0;
        foreach ($this->getStockmovements() as $movement){
            $stock += $movement->getQuantity();
        }

        return $stock;
    }

    /**
     * @return Collection<int, Receptionline>
     */
    public function getReceptionlines(): Collection
    {
        return $this->receptionlines;
    }

    public function addReceptionline(Receptionline $receptionline): static
    {
        if (!$this->receptionlines->contains($receptionline)) {
            $this->receptionlines->add($receptionline);
            $receptionline->setProduct($this);
        }

        return $this;
    }

    public function removeReceptionline(Receptionline $receptionline): static
    {
        if ($this->receptionlines->removeElement($receptionline)) {
            // set the owning side to null (unless already changed)
            if ($receptionline->getProduct() === $this) {
                $receptionline->setProduct(null);
            }
        }

        return $this;
    }
}
