<?php

namespace App\Entity;

use App\Repository\HomeDetailRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HomeDetailRepository::class)
 */
class HomeDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $generation;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $metier;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $communication;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $savoir;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $immobilier;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $design;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $innovation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGeneration(): ?string
    {
        return $this->generation;
    }

    public function setGeneration(?string $generation): self
    {
        $this->generation = $generation;

        return $this;
    }

    public function getMetier(): ?string
    {
        return $this->metier;
    }

    public function setMetier(?string $metier): self
    {
        $this->metier = $metier;

        return $this;
    }

    public function getCommunication(): ?string
    {
        return $this->communication;
    }

    public function setCommunication(?string $communication): self
    {
        $this->communication = $communication;

        return $this;
    }

    public function getSavoir(): ?string
    {
        return $this->savoir;
    }

    public function setSavoir(?string $savoir): self
    {
        $this->savoir = $savoir;

        return $this;
    }

    public function getImmobilier(): ?string
    {
        return $this->immobilier;
    }

    public function setImmobilier(?string $immobilier): self
    {
        $this->immobilier = $immobilier;

        return $this;
    }

    public function getDesign(): ?string
    {
        return $this->design;
    }

    public function setDesign(?string $design): self
    {
        $this->design = $design;

        return $this;
    }

    public function getInnovation(): ?string
    {
        return $this->innovation;
    }

    public function setInnovation(?string $innovation): self
    {
        $this->innovation = $innovation;

        return $this;
    }
}
