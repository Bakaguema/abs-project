<?php

namespace App\Entity;

use App\Repository\ExperienceSaveRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExperienceSaveRepository::class)
 */
class ExperienceSave
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Experience::class, inversedBy="saves")
     */
    private $experience;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="expSaves")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExperience(): ?Experience
    {
        return $this->experience;
    }

    public function setExperience(?Experience $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
