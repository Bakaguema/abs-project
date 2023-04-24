<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\FriendRepository;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=FriendRepository::class)
 * @UniqueEntity({"user1","user2"}, message="Vous êtes déjà ami")
 */
class Friend
{
   /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
      */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="friend")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user1;

    /**
    * @ORM\ManyToOne(targetEntity=User::class, inversedBy="friend")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user2;

    /**
     * @ORM\Column(type="boolean")
    
     */
    private $active;

    public function __construct()
    {
        $this->user2 = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|user[]
     */
    

    public function getuser1(): ?User
    {
        return $this->user1;
    }

    public function setUser1(?User $user1): self
    {
        $this->user1 = $user1;

        return $this;
    }
    public function getuser2(): ?User
    {
        return $this->user2;
    }

    
    public function setUser2(?User $user2): self
    {
        $this->user2 = $user2;

        return $this;
    }


    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}

