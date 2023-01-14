<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Titre;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Public;

    /**
     * @ORM\OneToMany(targetEntity=FlashCard::class, mappedBy="category", orphanRemoval=true)
     */
    private $ListCards;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="categories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    public function __construct()
    {
        $this->ListCards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): self
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->Public;
    }

    public function setPublic(bool $Public): self
    {
        $this->Public = $Public;

        return $this;
    }

    /**
     * @return Collection<int, FlashCard>
     */
    public function getListCards(): Collection
    {
        return $this->ListCards;
    }

    public function addListCard(FlashCard $listCard): self
    {
        if (!$this->ListCards->contains($listCard)) {
            $this->ListCards[] = $listCard;
            $listCard->setCategory($this);
        }

        return $this;
    }

    public function removeListCard(FlashCard $listCard): self
    {
        if ($this->ListCards->removeElement($listCard)) {
            // set the owning side to null (unless already changed)
            if ($listCard->getCategory() === $this) {
                $listCard->setCategory(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
}
