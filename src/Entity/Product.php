<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     * @Groups({"get_product_lite"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"get_product_lite"})
     */
    private $available;

    /**
     * @ORM\Column(type="float")
     */
    private $stock;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"get_product_lite"})
     */
    private $unitPrice;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"get_product_lite"})
     */
    private $weightPrice;

    /**
     * @ORM\Column(type="string", length=2083, nullable=true)
     * @Groups({"get_product_lite"})
     */
    private $picture;

    /**
     * @ORM\Column(type="float")
     */
    private $weight;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $hihlighted;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $online;

    /**
     * @ORM\OneToMany(targetEntity=ProductCommand::class, mappedBy="product", orphanRemoval=true)
     */
    private $productCommands;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

    public function __construct()
    {
        $this->productCommands = new ArrayCollection();
        $this->creationAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getAvailable(): ?int
    {
        return $this->available;
    }

    public function setAvailable(int $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(?float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getWeightPrice(): ?float
    {
        return $this->weightPrice;
    }

    public function setWeightPrice(?float $weightPrice): self
    {
        $this->weightPrice = $weightPrice;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreationAt(): ?\DateTimeInterface
    {
        return $this->creationAt;
    }

    public function setCreationAt(\DateTimeInterface $creationAt): self
    {
        $this->creationAt = $creationAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getHihlighted(): ?int
    {
        return $this->hihlighted;
    }

    public function setHihlighted(?int $hihlighted): self
    {
        $this->hihlighted = $hihlighted;

        return $this;
    }

    public function getOnline(): ?int
    {
        return $this->online;
    }

    public function setOnline(?int $online): self
    {
        $this->online = $online;

        return $this;
    }

    /**
     * @return Collection|ProductCommand[]
     */
    public function getProductCommands(): Collection
    {
        return $this->productCommands;
    }

    public function addProductCommand(ProductCommand $productCommand): self
    {
        if (!$this->productCommands->contains($productCommand)) {
            $this->productCommands[] = $productCommand;
            $productCommand->setProduct($this);
        }

        return $this;
    }

    public function removeProductCommand(ProductCommand $productCommand): self
    {
        if ($this->productCommands->removeElement($productCommand)) {
            // set the owning side to null (unless already changed)
            if ($productCommand->getProduct() === $this) {
                $productCommand->setProduct(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
