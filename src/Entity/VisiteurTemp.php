<?php

namespace App\Entity;

use App\Repository\VisiteurTempRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=VisiteurTempRepository::class)
 */
class VisiteurTemp
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
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $ip;

    /**
     * @ORM\Column(type="integer")
     */
    private $average;

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

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

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
}
