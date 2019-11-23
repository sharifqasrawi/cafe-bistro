<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    public const uploadFolder = 'assets/images/uploads/';
    public const perPage = 5;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Title is required")
     */
    private $Title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $header_image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $Deleted_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsDeleted;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="Category")
     */
    private $Items;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsOnHomePage;

    public function __construct()
    {
        $this->Items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getHeaderImage(): ?string
    {
        return $this->header_image;
    }

    public function setHeaderImage(?string $header_image): self
    {
        $this->header_image = $header_image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->Created_at;
    }

    public function setCreatedAt(\DateTimeInterface $Created_at): self
    {
        $this->Created_at = $Created_at;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->Deleted_at;
    }

    public function setDeletedAt(\DateTimeInterface $Deleted_at): self
    {
        $this->Deleted_at = $Deleted_at;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->IsDeleted;
    }

    public function setIsDeleted(bool $IsDeleted): self
    {
        $this->IsDeleted = $IsDeleted;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        $criteria = Criteria::create()
                    ->andWhere(Criteria::expr()->eq('IsDeleted', false))
                    ->orderBy(['Title' => 'ASC']);
        return $this->Items->matching($criteria);
    }

    public function getItemsHome(): Collection
    {
        $criteria = Criteria::create()
                    ->andWhere(Criteria::expr()->eq('IsDeleted', false))
                    ->orderBy(['Title' => 'ASC'])
                    ->setMaxResults(4);
        return $this->Items->matching($criteria);
    }

    public function addItem(Item $item): self
    {
        if (!$this->Items->contains($item)) {
            $this->Items[] = $item;
            $item->setCategory($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->Items->contains($item)) {
            $this->Items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getCategory() === $this) {
                $item->setCategory(null);
            }
        }

        return $this;
    }

    public function getIsOnHomePage(): ?bool
    {
        return $this->IsOnHomePage;
    }

    public function setIsOnHomePage(?bool $IsOnHomePage): self
    {
        $this->IsOnHomePage = $IsOnHomePage;

        return $this;
    }
}
