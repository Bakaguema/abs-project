<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Pole;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * 
 */
class Article
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=true)
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=true)
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Illustration::class, mappedBy="article", orphanRemoval=true,cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $illustrations;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="favorites")
     */
    private $favorite;

    /**
     * @ORM\OneToMany(targetEntity=Experience::class, mappedBy="article")
     * @ORM\JoinColumn(nullable=true)
     */
    private $experiences;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="article")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=ArticleLike::class, mappedBy="article")
     */
    private $likes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $rgpd;

    /**
     * @ORM\OneToMany(targetEntity=ArticleSave::class, mappedBy="article")
     */
    private $saves;

    /**
     * @ORM\Column(type="string", length=666, nullable=true)
     */
    private $video;

    // /**
    //  * @ORM\ManyToOne(targetEntity=Pole::class, inversedBy="articles")
    //  */
    // private $pole;

    /**
     * @ORM\ManyToMany(targetEntity=Pole::class, mappedBy="articles")
     */
    private Collection $poles;


    public function __construct()
    {
        $this->poles = new ArrayCollection();
        $this->illustrations = new ArrayCollection();
        $this->favorite = new ArrayCollection();
        $this->experiences = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->saves = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitle();
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

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getCategories(): ?Category
    {
        return $this->categories;
    }

    public function setCategories(?Category $categories): self
    {
        $this->categories = $categories;

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
            $illustration->setArticle($this);
        }

        return $this;
    }

    public function removeIllustration(Illustration $illustration): self
    {
        if ($this->illustrations->removeElement($illustration)) {
            // set the owning side to null (unless already changed)
            if ($illustration->getArticle() === $this) {
                $illustration->setArticle(null);
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

    /**
     * @return Collection|Experience[]
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): self
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences[] = $experience;
            $experience->setArticle($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getArticle() === $this) {
                $experience->setArticle(null);
            }
        }

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
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
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
     * @return Collection|ArticleLike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(ArticleLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setArticle($this);
        }

        return $this;
    }

    public function removeLike(ArticleLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getArticle() === $this) {
                $like->setArticle(null);
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
        foreach ($this->likes as $like) {
            if ($like->getUser() === $user) return true;
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
     * @return Collection|ArticleSave[]
     */
    public function getSaves(): Collection
    {
        return $this->saves;
    }

    public function addSave(ArticleSave $save): self
    {
        if (!$this->saves->contains($save)) {
            $this->saves[] = $save;
            $save->setArticle($this);
        }

        return $this;
    }

    public function removeSave(ArticleSave $save): self
    {
        if ($this->saves->removeElement($save)) {
            // set the owning side to null (unless already changed)
            if ($save->getArticle() === $this) {
                $save->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isSavedByUser(User $user): bool
    {
        foreach ($this->saves as $save) {
            if ($save->getUser() === $user) return true;
        }

        return false;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getPoles(): Collection
    {
        return $this->poles;
    }

    public function addPole(Pole $pole): self
    {
        if (!$this->poles->contains($pole)) {
            $this->poles[] = $pole;
            $pole->addArticle($this);
        }

        return $this;
    }
    public function removePole(Pole $pole): self
    {
        $this->poles->removeElement($pole); //Enleve le pole à la collection poles[] de Article
        $pole->removeArticle($this);//Enleve le pole à l' Article
        
        return $this;

    }


}
