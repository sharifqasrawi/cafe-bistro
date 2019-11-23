<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item
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
     * @ORM\Column(type="string", length=10000, nullable=true)
     * @Assert\NotBlank(message = "Description is required")
     */
    private $Description;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message = "Price is required")
     */
    private $Price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Created_by;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $Deleted_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsDeleted;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="Items")
     */
    private $Category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="Item")
     */
    private $comments;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsGallery;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(float $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(?string $Image): self
    {
        $this->Image = $Image;

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

    public function getCreatedBy(): ?string
    {
        return $this->Created_by;
    }

    public function setCreatedBy(string $Created_by): self
    {
        $this->Created_by = $Created_by;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->Deleted_at;
    }

    public function setDeletedAt(?\DateTimeInterface $Deleted_at): self
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

    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setItem($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getItem() === $this) {
                $comment->setItem(null);
            }
        }

        return $this;
    }

    public function getIsGallery(): ?bool
    {
        return $this->IsGallery;
    }

    public function setIsGallery(?bool $IsGallery): self
    {
        $this->IsGallery = $IsGallery;

        return $this;
    }
}
