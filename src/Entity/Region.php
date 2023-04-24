<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(RegionRepository::class)
 * @ORM\Table(name="region", indexes={
 * @Index(name="code", columns={"code"})
 *   })
 */

class Region
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */

    private $id = null;
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=3)
     */

    private  $code = null;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private  $name = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug = null;
    /**
     * @ORM\OneToMany(targetEntity=Departement::class, mappedBy="region",cascade={"remove"})
     */

    private $departements;

    public function __construct()
    {
        $this->departements = new ArrayCollection();
    }

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


    public function __toString()
    {
        return $this->name; // Remplacer champ par une propriété "string" de l'entité
    }


    /**
     * @return Collection<int, Departement>
     */
    public function getDepartements(): Collection
    {
        return $this->departements;
    }

    public function addDepartement(Departement $departement): self
    {
        if (!$this->departements->contains($departement)) {
            $this->departements->add($departement);
            $departement->setRegion($this);
        }

        return $this;
    }

    public function removeDepartement(Departement $departement): self
    {
        if ($this->departements->removeElement($departement)) {
            // set the owning side to null (unless already changed)
            if ($departement->getRegion() === $this) {
                $departement->setRegion(null);
            }
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
