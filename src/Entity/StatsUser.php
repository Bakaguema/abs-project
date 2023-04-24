<?php

namespace App\Entity;

use App\Repository\StatsUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatsUserRepository::class)
 */
class StatsUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=user::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $LikeArticle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLikeArticle(): ?int
    {
        return $this->LikeArticle;
    }

    public function setLikeArticle(int $LikeArticle): self
    {
        $this->LikeArticle = $LikeArticle;

        return $this;
    }
}
