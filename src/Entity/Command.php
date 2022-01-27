<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommandRepository::class)
 */
class Command
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numFact;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"command_info", "client_commands"})
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"command_info", "client_commands"})
     */
    private $creationAt;

    /**
     * @ORM\Column(type="float")
     * @Groups({"command_info", "client_commands"})
     */
    private $totalTTC;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalHT;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalTVA;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commands")
     * 
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=ProductCommand::class, mappedBy="command", orphanRemoval=true)
     */
    private $productCommands;

    public function __construct()
    {
        $this->productCommands = new ArrayCollection();
        $this->creationAt = new DateTime();
    }
    public function __toString() {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumFact(): ?string
    {
        return $this->numFact;
    }

    public function setNumFact(?string $numFact): self
    {
        $this->numFact = $numFact;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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

    public function getTotalTTC(): ?float
    {
        return $this->totalTTC;
    }

    public function setTotalTTC(float $totalTTC): self
    {
        $this->totalTTC = $totalTTC;

        return $this;
    }

    public function getTotalHT(): ?float
    {
        return $this->totalHT;
    }

    public function setTotalHT(?float $totalHT): self
    {
        $this->totalHT = $totalHT;

        return $this;
    }

    public function getTotalTVA(): ?float
    {
        return $this->totalTVA;
    }

    public function setTotalTVA(?float $totalTVA): self
    {
        $this->totalTVA = $totalTVA;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $productCommand->setCommand($this);
        }

        return $this;
    }

    public function removeProductCommand(ProductCommand $productCommand): self
    {
        if ($this->productCommands->removeElement($productCommand)) {
            // set the owning side to null (unless already changed)
            if ($productCommand->getCommand() === $this) {
                $productCommand->setCommand(null);
            }
        }

        return $this;
    }
}
