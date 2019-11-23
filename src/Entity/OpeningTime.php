<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OpeningTimeRepository")
 */
class OpeningTime
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Day is required")
     */
    private $Day;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Open hour is required")
     */
    private $Open_hour;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Close hour is required")
     */
    private $Close_hour;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank(message = "Day order is required")
     * @Assert\Positive
     */
    private $DayOrder;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?string
    {
        return $this->Day;
    }

    public function setDay(string $Day): self
    {
        $this->Day = $Day;

        return $this;
    }

    public function getOpenHour(): ?string
    {
        return $this->Open_hour;
    }

    public function setOpenHour(string $Open_hour): self
    {
        $this->Open_hour = $Open_hour;

        return $this;
    }

    public function getCloseHour(): ?string
    {
        return $this->Close_hour;
    }

    public function setCloseHour(string $Close_hour): self
    {
        $this->Close_hour = $Close_hour;

        return $this;
    }

    public function getDayOrder(): ?int
    {
        return $this->DayOrder;
    }

    public function setDayOrder(int $DayOrder): self
    {
        $this->DayOrder = $DayOrder;

        return $this;
    }
}
