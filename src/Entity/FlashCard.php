<?php

namespace App\Entity;

use App\Repository\FlashCardRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FlashCardRepository::class)
 */
class FlashCard
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $Front;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Back;

    /**
     * @ORM\Column(type="integer")
     */
    private $Type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Successfull;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Error;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Success;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="ListCards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFront(): ?string
    {
        return $this->Front;
    }

    public function setFront(string $Front): self
    {
        $this->Front = $Front;

        return $this;
    }

    public function getBack(): ?string
    {
        return $this->Back;
    }

    public function setBack(string $Back): self
    {
        $this->Back = $Back;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->Type;
    }

    public function setType(int $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getSuccessfull(): ?int
    {
        return $this->Successfull;
    }

    public function setSuccessfull(int $Successfull): self
    {
        $this->Successfull = $Successfull;

        return $this;
    }

    public function isSuccess(): ?bool
    {
        return $this->Success;
    }

    public function setSuccess(bool $Success): self
    {
        $this->Success = $Success;

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

    public function getError(): ?int
    {
        return $this->Error;
    }

    public function setError(?int $Error): self
    {
        $this->Error = $Error;

        return $this;
    }

    public function addSuccess(): self
    {
        $this->setSuccessfull($this->getSuccessfull() + 1);
        return $this;
    }

    public function addError(): self
    {
        $this->setError($this->getError() + 1);
        return $this;
    }
}
