<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_product_lite","product","product_info"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     * @Groups({"get_product_lite", "product","product_info"})
     * @Assert\NotBlank(message = "Le champ '{{ label }}' ne peut être vide.")
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"product","product_info"})
     */
    private $summary;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"get_product_lite", "product", "product_info"})
     * @Assert\NotBlank(message = "Le champ '{{ label }}' ne peut être vide.")
     */
    private $available;

    /**
     * @ORM\Column(type="float")
     * @Groups({"product", "product_info"})
     * @Assert\NotBlank(message = "Le champ '{{ label }}' ne peut être vide.")
     */
    private $stock;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"product", "product_info"})
     */
    private $description;

     

    /**
     * @ORM\Column(type="string", length=2083, nullable=true)
     * @Groups({"get_product_lite", "product", "product_info"})
     */
    private $picture;

    /**
     * @ORM\Column(type="float", nullable=true  )
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
     * @Groups({"get_product_lite", "product", "product_info"})
     */
    private $slug;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $hihlighted;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * 
     */
    private $online;

    /**
     * @ORM\OneToMany(targetEntity=ProductCommand::class, mappedBy="product", orphanRemoval=true)
     * 
     */
    private $productCommands;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @Groups({"get_product_lite", "product", "product_info"})
     * @Assert\NotBlank(message = "Le champ '{{ label }}' ne peut être vide.")
     */
    private $category;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"get_product_lite", "product", "product_info"})
     * @Assert\NotBlank(message = "Le champ '{{ label }}' ne peut être vide.")
     */
    private $price;

    /**
     * @ORM\Column(type="smallint", options={"default":0})
     * @Groups({"get_product_lite", "product", "product_info"})
     * @Assert\NotBlank(message = "Le champ '{{ label }}' ne peut être vide.")
     */
    private $unitType;

    public function __construct()
    {
        $this->productCommands = new ArrayCollection();
        $this->creationAt = new DateTime();
    }
    public function __toString() {
        return (!empty($this->name)?$this->name:"");
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
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

    public function setAvailable(?int $available): self
    {
        $this->available = $available;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
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

    public function setWeight(?float $weight): self
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getUnitType(): ?int
    {
        return $this->unitType;
    }

    public function setUnitType(?int $unitType): self
    {
        $this->unitType = $unitType;

        return $this;
    }
}
