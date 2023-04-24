<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ExperienceRepository::class)
 */
class Experience
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
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="experiences")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="experiences")
     * @ORM\JoinColumn(nullable=true)
     */
    private $article;

    /**
     * @ORM\OneToMany(targetEntity=Illustration::class, mappedBy="experience", orphanRemoval=true, cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $illustrations;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="favoritesExp")
     */
    private $favorite;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="experience")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=ExperienceLike::class, mappedBy="experience")
     */
    private $likes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $rgpd;

    /**
     * @ORM\OneToMany(targetEntity=ExperienceSave::class, mappedBy="experience")
     */
    private $saves;


    public function __construct()
    {
        $this->illustrations = new ArrayCollection();
        $this->favorite = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->saves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
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

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return Collection|Illustration[]
     */
    public function getIllustrations(): Collection
    {
        return $this->illustrations;
    }

    public function addIllustration(Illustration $illustration): self
    {
        if (!$this->illustrations->contains($illustration)) {
            $this->illustrations[] = $illustration;
            $illustration->setExperience($this);
        }

        return $this;
    }

    public function removeIllustration(Illustration $illustration): self
    {
        if ($this->illustrations->removeElement($illustration)) {
            // set the owning side to null (unless already changed)
            if ($illustration->getExperience() === $this) {
                $illustration->setExperience(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFavorite(): Collection
    {
        return $this->favorite;
    }

    public function addFavorite(User $favorite): self
    {
        if (!$this->favorite->contains($favorite)) {
            $this->favorite[] = $favorite;
        }

        return $this;
    }

    public function removeFavorite(User $favorite): self
    {
        $this->favorite->removeElement($favorite);

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

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setExperience($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getExperience() === $this) {
                $comment->setExperience(null);
            }
        }

        return $this;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|ExperienceLike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(ExperienceLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setExperience($this);
        }

        return $this;
    }

    public function removeLike(ExperienceLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getExperience() === $this) {
                $like->setExperience(null);
            }
        }

        return $this;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isLikedbyUser(User $user): bool
    {
        foreach ($this->likes as $likes) {
            if ($likes->getUser() === $user) return true;
        }

        return false;
    }

    public function getRgpd(): ?bool
    {
        return $this->rgpd;
    }

    public function setRgpd(bool $rgpd): self
    {
        $this->rgpd = $rgpd;

        return $this;
    }

    /**
     * @return Collection|ExperienceSave[]
     */
    public function getSaves(): Collection
    {
        return $this->saves;
    }

    public function addSave(ExperienceSave $save): self
    {
        if (!$this->saves->contains($save)) {
            $this->saves[] = $save;
            $save->setExperience($this);
        }

        return $this;
    }

    public function removeSave(ExperienceSave $save): self
    {
        if ($this->saves->removeElement($save)) {
            // set the owning side to null (unless already changed)
            if ($save->getExperience() === $this) {
                $save->setExperience(null);
            }
        }

        return $this;
    }

    public function isSavedByUser(User $user): bool
    {
        foreach ($this->saves as $save) {
            if ($save->getUser() === $user) return true;
        }

        return false;
    }

    public function __toString()
{
    return (string) $this->getTitle();
}

}
