<?php

namespace App\Entity;


use App\Repository\CodeRedeemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CodeRedeemRepository::class)
 */
class CodeRedeem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10, unique=true)
     */
    private $Code;

    /**
     * @ORM\Column(type="integer")
     */
    private $Utilisation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="redeem")
     */
    private $users;

    /**
     * @ORM\Column(type="integer")
     */
    private $MaxUsage;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->Code;
    }

    public function setCode(string $Code): self
    {
        $this->Code = $Code;

        return $this;
    }

    public function getUtilisation(): ?int
    {
        return $this->Utilisation;
    }

    public function setUtilisation( $Utilisation): self
    {
        $this->Utilisation = $Utilisation;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setRedeem($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getRedeem() === $this) {
                $user->setRedeem(null);
            }
        }

        return $this;
    }

    public function getMaxUsage(): ?int
    {
        return $this->MaxUsage;
    }

    public function setMaxUsage(int $MaxUsage): self
    {
        $this->MaxUsage = $MaxUsage;

        return $this;
    }
}
