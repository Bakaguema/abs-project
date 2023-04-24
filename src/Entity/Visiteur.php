<?php

namespace App\Entity;

use App\Repository\VisiteurRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=VisiteurRepository::class)
 */
class Visiteur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $average;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbvisit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAverage(): ?int
    {
        return $this->average;
    }

    public function setAverage(int $average): self
    {
        $this->average = $average;

        return $this;
    }

    public function getNbvisit(): ?int
    {
        return $this->nbvisit;
    }

    public function setNbvisit(int $nbvisit): self
    {
        $this->nbvisit = $nbvisit;

        return $this;
    }
}
