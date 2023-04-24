<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(CityRepository::class)
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    
    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $insee_code;
    
    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $zip_code;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;
    
    /**
     * @ORM\Column(type="float")
     */
    private $gps_lat;
    
    /**
     * @ORM\Column(type="float")
     */
    private $gps_lng;
    
    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="city")
     */
    private $users;
    
    /**
     * @ORM\ManyToOne(targetEntity=Departement::class, inversedBy="cities")
     * @ORM\JoinColumn(name="departement_code", referencedColumnName="code", nullable=false)
     */
    private $departement;
    
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInseeCode(): ?string
    {
        return $this->insee_code;
    }

    public function setInseeCode(?string $insee_code): self
    {
        $this->insee_code = $insee_code;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zip_code;
    }

    public function setZipCode(?string $zip_code): self
    {
        $this->zip_code = $zip_code;

        return $this;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getGpsLat(): ?float
    {
        return $this->gps_lat;
    }

    public function setGpsLat(float $gps_lat): self
    {
        $this->gps_lat = $gps_lat;

        return $this;
    }

    public function getGpsLng(): ?float
    {
        return $this->gps_lng;
    }

    public function setGpsLng(float $gps_lng): self
    {
        $this->gps_lng = $gps_lng;

        return $this;
    }

    // public function getDepartement(): ?Departement
    // {
    //     return $this->departement;
    // }

    // public function setDepartement(?Departement $departement): self
    // {
    //     $this->departement = $departement;

    //     return $this;
    // }

    
    public function __toString(){
        return $this->name . ' ' . $this->zip_code; // Remplacer champ par une propriété "string" de l'entité
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
            $user->setCity($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCity() === $this) {
                $user->setCity(null);
            }
        }

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

}
