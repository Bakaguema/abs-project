<?php

namespace App\Entity;

use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(DepartementRepository::class)
 * @ORM\Table(name="departement", indexes={
 *      @Index(name="code", columns={"code"})
 *     })
 */

class Departement
{
    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */

    private $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="departements")
     * @ORM\JoinColumn(name="region_code", referencedColumnName="code", nullable=false)
     */

    private $region = null;

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
     * @ORM\OneToMany(targetEntity=City::class, mappedBy="departement", orphanRemoval=true)
     */
    private $cities;

    public function __construct()
    {
        $this->cities = new ArrayCollection();
    }
    /**
     * @ORM\OneToMany(targetEntity=City::class, mappedBy="departement",cascade={"remove"})
     */

    // private $cities;



    // public function __construct()
    // {
    //     $this->cities = new ArrayCollection();
    // }

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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }


    public function __toString()
    {
        return $this->name; // Remplacer champ par une propriété "string" de l'entité
    }

    // /**
    //  * @return Collection<int, City>
    //  */
    // public function getCities(): Collection
    // {
    //     return $this->cities;
    // }

    // public function addCity(City $city): self
    // {
    //     if (!$this->cities->contains($city)) {
    //         $this->cities->add($city);
    //         $city->setDepartement($this);
    //     }

    //     return $this;
    // }

    // public function removeCity(City $city): self
    // {
    //     if ($this->cities->removeElement($city)) {
    //         // set the owning side to null (unless already changed)
    //         if ($city->getDepartement() === $this) {
    //             $city->setDepartement(null);
    //         }
    //     }

    //     return $this;
    // }

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

    /**
     * @return Collection<int, City>
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(City $city): self
    {
        if (!$this->cities->contains($city)) {
            $this->cities[] = $city;
            $city->setDepartement($this);
        }

        return $this;
    }

    public function removeCity(City $city): self
    {
        if ($this->cities->removeElement($city)) {
            // set the owning side to null (unless already changed)
            if ($city->getDepartement() === $this) {
                $city->setDepartement(null);
            }
        }

        return $this;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
