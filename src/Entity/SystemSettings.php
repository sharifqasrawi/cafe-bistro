<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SystemSettingsRepository")
 */
class SystemSettings
{

    public const uploadFolder = 'assets/images/uploads/';


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $AppName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $AppLogo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $AppInfo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Address_Street;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Address_City;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Address_Country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $HomePage_Background;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Facebook_Page_Iframe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Google_Iframe;

    

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Aboutus_Image;

    /**
     * @ORM\Column(type="string", length=5000, nullable=true)
     */
    private $Aboutus_Text;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $Twitter_Link;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $Instagram_Link;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $Facebook_Link;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $AppInfo2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $login_page_background;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFirstRun;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message = "Menu cover height is required")
     */
    private $menu_cover_height;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message = "Menu page height is required")
     */
    private $menu_page_height;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gallery_header_image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location_header_image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contact_header_image;

    /**
     * @ORM\Column(type="float", length=255, nullable=true)
     */
    private $locationLongitude;

    /**
     * @ORM\Column(type="float", length=255, nullable=true)
     */
    private $locationLatitude;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppName(): ?string
    {
        return $this->AppName;
    }

    public function setAppName(string $AppName): self
    {
        $this->AppName = $AppName;

        return $this;
    }

    public function getAppLogo(): ?string
    {
        return $this->AppLogo;
    }

    public function setAppLogo(?string $AppLogo): self
    {
        $this->AppLogo = $AppLogo;

        return $this;
    }

    public function getAppInfo(): ?string
    {
        return $this->AppInfo;
    }

    public function setAppInfo(?string $AppInfo): self
    {
        $this->AppInfo = $AppInfo;

        return $this;
    }

    public function getAddressStreet(): ?string
    {
        return $this->Address_Street;
    }

    public function setAddressStreet(string $Address_Street): self
    {
        $this->Address_Street = $Address_Street;

        return $this;
    }

    public function getAddressCity(): ?string
    {
        return $this->Address_City;
    }

    public function setAddressCity(string $Address_City): self
    {
        $this->Address_City = $Address_City;

        return $this;
    }

    public function getAddressCountry(): ?string
    {
        return $this->Address_Country;
    }

    public function setAddressCountry(string $Address_Country): self
    {
        $this->Address_Country = $Address_Country;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(?string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->Phone;
    }

    public function setPhone(?string $Phone): self
    {
        $this->Phone = $Phone;

        return $this;
    }

    public function getHomePageBackground(): ?string
    {
        return $this->HomePage_Background;
    }

    public function setHomePageBackground(?string $HomePage_Background): self
    {
        $this->HomePage_Background = $HomePage_Background;

        return $this;
    }

    public function getFacebookPageIframe(): ?string
    {
        return $this->Facebook_Page_Iframe;
    }

    public function setFacebookPageIframe(?string $Facebook_Page_Iframe): self
    {
        $this->Facebook_Page_Iframe = $Facebook_Page_Iframe;

        return $this;
    }

    public function getGoogleIframe(): ?string
    {
        return $this->Google_Iframe;
    }

    public function setGoogleIframe(?string $Google_Iframe): self
    {
        $this->Google_Iframe = $Google_Iframe;

        return $this;
    }

    public function getAboutusText(): ?string
    {
        return $this->Aboutus_Text;
    }

    public function setAboutusText(string $Aboutus_Text): self
    {
        $this->Aboutus_Text = $Aboutus_Text;

        return $this;
    }

    public function getAboutusImage(): ?string
    {
        return $this->Aboutus_Image;
    }

    public function setAboutusImage(?string $Aboutus_Image): self
    {
        $this->Aboutus_Image = $Aboutus_Image;

        return $this;
    }

    public function getTwitterLink(): ?string
    {
        return $this->Twitter_Link;
    }

    public function setTwitterLink(?string $Twitter_Link): self
    {
        $this->Twitter_Link = $Twitter_Link;

        return $this;
    }

    public function getInstagramLink(): ?string
    {
        return $this->Instagram_Link;
    }

    public function setInstagramLink(?string $Instagram_Link): self
    {
        $this->Instagram_Link = $Instagram_Link;

        return $this;
    }

    public function getFacebookLink(): ?string
    {
        return $this->Facebook_Link;
    }

    public function setFacebookLink(?string $Facebook_Link): self
    {
        $this->Facebook_Link = $Facebook_Link;

        return $this;
    }

    public function getAppInfo2(): ?string
    {
        return $this->AppInfo2;
    }

    public function setAppInfo2(?string $AppInfo2): self
    {
        $this->AppInfo2 = $AppInfo2;

        return $this;
    }

    public function getLoginPageBackground(): ?string
    {
        return $this->login_page_background;
    }

    public function setLoginPageBackground(?string $login_page_background): self
    {
        $this->login_page_background = $login_page_background;

        return $this;
    }

    public function getIsFirstRun(): ?bool
    {
        return $this->isFirstRun;
    }

    public function setIsFirstRun(bool $isFirstRun): self
    {
        $this->isFirstRun = $isFirstRun;

        return $this;
    }

    public function getMenuCoverHeight(): ?int
    {
        return $this->menu_cover_height;
    }

    public function setMenuCoverHeight(?int $menu_cover_height): self
    {
        $this->menu_cover_height = $menu_cover_height;

        return $this;
    }

    public function getMenuPageHeight(): ?int
    {
        return $this->menu_page_height;
    }

    public function setMenuPageHeight(int $menu_page_height): self
    {
        $this->menu_page_height = $menu_page_height;

        return $this;
    }

    public function getGalleryHeaderImage(): ?string
    {
        return $this->gallery_header_image;
    }

    public function setGalleryHeaderImage(?string $gallery_header_image): self
    {
        $this->gallery_header_image = $gallery_header_image;

        return $this;
    }

    public function getLocationHeaderImage(): ?string
    {
        return $this->location_header_image;
    }

    public function setLocationHeaderImage(?string $location_header_image): self
    {
        $this->location_header_image = $location_header_image;

        return $this;
    }

    public function getContactHeaderImage(): ?string
    {
        return $this->contact_header_image;
    }

    public function setContactHeaderImage(?string $contact_header_image): self
    {
        $this->contact_header_image = $contact_header_image;

        return $this;
    }

    public function getLocationLongitude(): ?float
    {
        return $this->locationLongitude;
    }

    public function setLocationLongitude(?string $locationLongitude): self
    {
        $this->locationLongitude = $locationLongitude;

        return $this;
    }

    public function getLocationLatitude(): ?float
    {
        return $this->locationLatitude;
    }

    public function setLocationLatitude(string $locationLatitude): self
    {
        $this->locationLatitude = $locationLatitude;

        return $this;
    }
}
