<?php

namespace App\Entity;

use App\Repository\PoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Article;

/**
 * @ORM\Entity(repositoryClass=PoleRepository::class)
 */
class Pole
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Pole::class, inversedBy="poles")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Pole::class, mappedBy="parent")
     */
    private $poles;


    // /**
    //  * @ORM\OneToMany(targetEntity=Article::class, mappedBy="pole")
    //  */
    // private $articles;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="pole")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, inversedBy="poles")
     * 
     */
    private Collection $articles;

    /**
     * @ORM\OneToOne(targetEntity=user::class, cascade={"persist", "remove"})
     */
    private $admin;

    public function __construct()
    {
        $this->poles = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getPoles(): Collection
    {
        return $this->poles;
    }

    public function addPole(self $pole): self
    {
        if (!$this->poles->contains($pole)) {
            $this->poles[] = $pole;
            $pole->setParent($this);
        }

        return $this;
    }

    public function removePole(self $pole): self
    {
        if ($this->poles->removeElement($pole)) {
            // set the owning side to null (unless already changed)
            if ($pole->getParent() === $this) {
                $pole->setParent(null);
            }
        }

        return $this;
    }


    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            // $article->setPoles($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        $this->articles->removeElement($article);
        return $this;
    }

    // public function addArticle(Article $article): self
    // {
    //     if (!$this->articles->contains($article)) {
    //         $this->articles[] = $article;
    //         $article->setPoles($this);
    //     }

    //     return $this;
    // }

    // public function removeArticle(Article $article): self
    // {
    //     if ($this->articles->removeElement($article)) {
    //         // set the owning side to null (unless already changed)
    //         if ($article->getPole() === $this) {
    //             $article->setPole(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addPole($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removePole($this);
        }

        return $this;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function getAdmin(): ?user
    {
        return $this->admin;
    }

    public function setAdmin(?user $admin): self
    {
        $this->admin = $admin;

        return $this;
    }
}
