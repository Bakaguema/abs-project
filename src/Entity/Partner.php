<?php

namespace App\Entity;

use App\Repository\PartnerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PartnerRepository::class)
 */
class Partner
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
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $resume;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $parcours;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $rencontres;

        /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ORM\ManyToMany(targetEntity=Partner::class, mappedBy="pole")
     */
    private $pole;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pole_1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pole_2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pole_3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pole_4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pole_5;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $profil;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $illustration;
        /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkedin;

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

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    public function getParcours(): ?string
    {
        return $this->parcours;
    }

    public function setParcours(?string $parcours): self
    {
        $this->parcours = $parcours;

        return $this;
    }

    public function getRencontres(): ?string
    {
        return $this->rencontres;
    }

    public function setRencontres(?string $rencontres): self
    {
        $this->rencontres = $rencontres;

        return $this;
    }

    public function getPole(): ?string
    {
        return $this->pole;
    }

    public function setPole(?string $pole): self
    {
        $this->pole = $pole;

        return $this;
    }

    public function getPole1(): ?string
    {
        return $this->pole_1;
    }

    public function setPole1(?string $pole_1): self
    {
        $this->pole_1 = $pole_1;

        return $this;
    }
    public function getPole2(): ?string
    {
        return $this->pole_2;
    }

    public function setPole2(?string $pole_2): self
    {
        $this->pole_2 = $pole_2;

        return $this;
    }
    public function getPole3(): ?string
    {
        return $this->pole_3;
    }

    public function setPole3(?string $pole_3): self
    {
        $this->pole_3 = $pole_3;

        return $this;
    }
    public function getPole4(): ?string
    {
        return $this->pole_4;
    }

    public function setPole4(?string $pole_4): self
    {
        $this->pole_4 = $pole_4;

        return $this;
    }
    public function getPole5(): ?string
    {
        return $this->pole_5;
    }

    public function setPole5(?string $pole_5): self
    {
        $this->pole_5 = $pole_5;

        return $this;
    }

    public function getProfil(): ?string
    {
        return $this->profil;
    }

    public function setProfil(string $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getIllustration(): ?string
    {
        return $this->illustration;
    }

    public function setIllustration(?string $illustration): self
    {
        $this->illustration = $illustration;

        return $this;
    }
    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }
}
