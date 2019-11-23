<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{

    public const perPage = 9;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Item", inversedBy="comments")
     */
    private $Item;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $User_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $User_email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isTestimonial;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): ?Item
    {
        return $this->Item;
    }

    public function setItem(?Item $Item): self
    {
        $this->Item = $Item;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->Text;
    }

    public function setText(string $Text): self
    {
        $this->Text = $Text;

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

    public function getUserName(): ?string
    {
        return $this->User_name;
    }

    public function setUserName(string $User_name): self
    {
        $this->User_name = $User_name;

        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->User_email;
    }

    public function setUserEmail(string $User_email): self
    {
        $this->User_email = $User_email;

        return $this;
    }

    public function getIsTestimonial(): ?bool
    {
        return $this->isTestimonial;
    }

    public function setIsTestimonial(bool $isTestimonial): self
    {
        $this->isTestimonial = $isTestimonial;

        return $this;
    }
}
