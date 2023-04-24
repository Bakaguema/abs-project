<?php

namespace App\Entity;

use App\Repository\SignalementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
    * @ORM\Entity(repositoryClass=SignalementRepository::class)
    *
*/
class Signalement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=user::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=comment::class,cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $content;


    /**
     * @ORM\Column(type="string", length=1000)
     * 
     */
    private $autre;

    /**
     * @ORM\Column(type="boolean")
     */
    private $view = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getComment(): ?comment
    {
        return $this->comment;
    }

    public function setComment(?comment $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAutre(): ?string
    {
        return $this->autre;
    }

    public function setAutre(string $autre): self
    {
        $this->autre = $autre;

        return $this;
    }

    public function getView(): ?bool
    {
        return $this->view;
    }

    public function setView(bool $view): self
    {
        $this->view = $view;

        return $this;
    }
}
