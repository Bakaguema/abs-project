<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Cet email existe déjà."
 * )
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"message", "messagerie", "messagerieNotif"})
     */
    private $id;

    /**
     * @Assert\Email()
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @Assert\NotBlank
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     * @Groups({"message", "messagerie"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"message", "messagerie"})
     */
    private $lastname;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="users",cascade={"remove"})
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity=Media::class, mappedBy="users")
     */
    private $medias;
    /**
     * @ORM\ManyToMany(targetEntity=Article::class, mappedBy="favorite")
     */
    private $favorites;

    /**
     * @ORM\OneToMany(targetEntity=Experience::class, mappedBy="user",cascade={"remove"})
     */
    private $experiences;

    /**
     * @ORM\ManyToMany(targetEntity=Experience::class, mappedBy="favorite")
     */
    private $favoritesExp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("messagerie")
     */
    private $picture="https://api.dicebear.com/5.x/adventurer-neutral/svg?seed=Felix";

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $document;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user",cascade={"remove"})
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity=ArticleLike::class, mappedBy="user")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity=ExperienceLike::class, mappedBy="user")
     */
    private $expLikes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $conditions;

    /**
     * @ORM\OneToMany(targetEntity=ArticleSave::class, mappedBy="user")
     */
    private $saves;

    /**
     * @ORM\OneToMany(targetEntity=ExperienceSave::class, mappedBy="user")
     */
    private $expSaves;

    /**
     * @ORM\OneToMany(targetEntity=Forum::class, mappedBy="user")
     */
    private $forums;

    /**
     * @ORM\OneToMany(targetEntity=ForumMessage::class, mappedBy="user")
     */
    private $forumMessages;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bio;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $profil;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $instagram;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkedin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pinterest;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $snapchat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tiktok;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $youtube;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $metier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $situation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caractere;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $interet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $objectif;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $insight;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_subscribe=false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pole;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pole_1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pole_2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pole_3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pole_4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pole_5;

    /**
     *  @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="User")
     */
    private $documents;

    /**
     * @ORM\ManyToOne(targetEntity=CodeRedeem::class, inversedBy="users")
     */
    private $redeem;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="users")
     */
    private $city;

    /**
     * @ORM\ManyToMany(targetEntity=Badge::class, inversedBy="users")
     */
    private $badges;

    /**
     * @ORM\ManyToMany(targetEntity=Chat::class, mappedBy="users")
     */
    private $chats;


   

   

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->active = false;
        $this->articles = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->experiences = new ArrayCollection();
        $this->favoritesExp = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->expLikes = new ArrayCollection();
        $this->saves = new ArrayCollection();
        $this->expSaves = new ArrayCollection();
        $this->forums = new ArrayCollection();
        $this->forumMessages = new ArrayCollection();
        $this->pole = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->medias = new ArrayCollection();

        
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }


    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUsers($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUsers() === $this) {
                $article->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Article $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
            $favorite->addFavorite($this);
        }

        return $this;
    }

    public function removeFavorite(Article $favorite): self
    {
        if ($this->favorites->removeElement($favorite)) {
            $favorite->removeFavorite($this);
        }

        return $this;
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedias(Media $media): self
    {
        if (!$this->medias->contains($media)) {
            $this->medias[] = $media;
            $media->setUser($this);
        }

        return $this;
    }

    public function removeMedias(Media $media): self
    {
        if ($this->medias->removeElement($media)) {
            // set the owning side to null (unless already changed)
            if ($media->getUser() === $this) {
                $media->setUser(null);
            }
        }

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
            $experience->setUser($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getUser() === $this) {
                $experience->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Experience[]
     */
    public function getFavoritesExp(): Collection
    {
        return $this->favoritesExp;
    }

    public function addFavoritesExp(Experience $favoritesExp): self
    {
        if (!$this->favoritesExp->contains($favoritesExp)) {
            $this->favoritesExp[] = $favoritesExp;
            $favoritesExp->addFavorite($this);
        }

        return $this;
    }

    public function removeFavoritesExp(Experience $favoritesExp): self
    {
        if ($this->favoritesExp->removeElement($favoritesExp)) {
            $favoritesExp->removeFavorite($this);
        }

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(?string $document): self
    {
        $this->document = $document;

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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

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
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(ArticleLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ExperienceLike[]
     */
    public function getExpLikes(): Collection
    {
        return $this->expLikes;
    }

    public function addExpLike(ExperienceLike $expLike): self
    {
        if (!$this->expLikes->contains($expLike)) {
            $this->expLikes[] = $expLike;
            $expLike->setUser($this);
        }

        return $this;
    }

    public function removeExpLike(ExperienceLike $expLike): self
    {
        if ($this->expLikes->removeElement($expLike)) {
            // set the owning side to null (unless already changed)
            if ($expLike->getUser() === $this) {
                $expLike->setUser(null);
            }
        }

        return $this;
    }

    public function getConditions(): ?bool
    {
        return $this->conditions;
    }

    public function setConditions(bool $conditions): self
    {
        $this->conditions = $conditions;

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
            $save->setUser($this);
        }

        return $this;
    }

    public function removeSave(ArticleSave $save): self
    {
        if ($this->saves->removeElement($save)) {
            // set the owning side to null (unless already changed)
            if ($save->getUser() === $this) {
                $save->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ExperienceSave[]
     */
    public function getExpSaves(): Collection
    {
        return $this->expSaves;
    }

    public function addExpSave(ExperienceSave $expSave): self
    {
        if (!$this->expSaves->contains($expSave)) {
            $this->expSaves[] = $expSave;
            $expSave->setUser($this);
        }

        return $this;
    }

    public function removeExpSave(ExperienceSave $expSave): self
    {
        if ($this->expSaves->removeElement($expSave)) {
            // set the owning side to null (unless already changed)
            if ($expSave->getUser() === $this) {
                $expSave->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Forum[]
     */
    public function getForums(): Collection
    {
        return $this->forums;
    }

    public function addForum(Forum $forum): self
    {
        if (!$this->forums->contains($forum)) {
            $this->forums[] = $forum;
            $forum->setUser($this);
        }

        return $this;
    }

    public function removeForum(Forum $forum): self
    {
        if ($this->forums->removeElement($forum)) {
            // set the owning side to null (unless already changed)
            if ($forum->getUser() === $this) {
                $forum->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ForumMessage[]
     */
    public function getForumMessages(): Collection
    {
        return $this->forumMessages;
    }

    public function addForumMessage(ForumMessage $forumMessage): self
    {
        if (!$this->forumMessages->contains($forumMessage)) {
            $this->forumMessages[] = $forumMessage;
            $forumMessage->setUser($this);
        }

        return $this;
    }

    public function removeForumMessage(ForumMessage $forumMessage): self
    {
        if ($this->forumMessages->removeElement($forumMessage)) {
            // set the owning side to null (unless already changed)
            if ($forumMessage->getUser() === $this) {
                $forumMessage->setUser(null);
            }
        }

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getProfil(): ?string
    {
        return $this->profil;
    }

    public function setProfil(?string $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getPinterest(): ?string
    {
        return $this->pinterest;
    }

    public function setPinterest(?string $pinterest): self
    {
        $this->pinterest = $pinterest;

        return $this;
    }

    public function getSnapchat(): ?string
    {
        return $this->snapchat;
    }

    public function setSnapchat(?string $snapchat): self
    {
        $this->snapchat = $snapchat;

        return $this;
    }

    public function getTiktok(): ?string
    {
        return $this->tiktok;
    }

    public function setTiktok(?string $tiktok): self
    {
        $this->tiktok = $tiktok;

        return $this;
    }

    public function getYoutube(): ?string
    {
        return $this->youtube;
    }

    public function setYoutube(?string $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }

    public function getAge(): ?\DateTimeInterface
    {
        return $this->age;
    }

    public function setAge(?\DateTimeInterface $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getMetier(): ?string
    {
        return $this->metier;
    }

    public function setMetier(?string $metier): self
    {
        $this->metier = $metier;

        return $this;
    }

    public function getSituation(): ?string
    {
        return $this->situation;
    }

    public function setSituation(?string $situation): self
    {
        $this->situation = $situation;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCaractere(): ?string
    {
        return $this->caractere;
    }

    public function setCaractere(?string $caractere): self
    {
        $this->caractere = $caractere;

        return $this;
    }

    public function getInteret(): ?string
    {
        return $this->interet;
    }

    public function setInteret(?string $interet): self
    {
        $this->interet = $interet;

        return $this;
    }

    public function getObjectif(): ?string
    {
        return $this->objectif;
    }

    public function setObjectif(?string $objectif): self
    {
        $this->objectif = $objectif;

        return $this;
    }

    public function getInsight(): ?string
    {
        return $this->insight;
    }

    public function setInsight(?string $insight): self
    {
        $this->insight = $insight;

        return $this;
    }

    public function getIsSubscribe(): ?bool
    {
        return $this->is_subscribe;
    }

    public function setIsSubscribe(bool $is_subscribe): self
    {
        $this->is_subscribe = $is_subscribe;

        return $this;
    }

    public function getPole(): ?string
    {
        return $this->pole;
    }

    public function setPole(?string $pole): self
    {
        $this->pole = $pole;

        return $this;
    }

    public function getPole1(): ?string
    {
        return $this->pole_1;
    }

    public function setPole1(?string $pole_1): self
    {
        $this->pole_1 = $pole_1;

        return $this;
    }
    public function getPole2(): ?string
    {
        return $this->pole_2;
    }

    public function setPole2(?string $pole_2): self
    {
        $this->pole_2 = $pole_2;

        return $this;
    }
    public function getPole3(): ?string
    {
        return $this->pole_3;
    }

    public function setPole3(?string $pole_3): self
    {
        $this->pole_3 = $pole_3;

        return $this;
    }
    public function getPole4(): ?string
    {
        return $this->pole_4;
    }

    public function setPole4(?string $pole_4): self
    {
        $this->pole_4 = $pole_4;

        return $this;
    }
    public function getPole5(): ?string
    {
        return $this->pole_5;
    }

    public function setPole5(?string $pole_5): self
    {
        $this->pole_5 = $pole_5;

        return $this;
    }
    
    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setUser($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getUser() === $this) {
                $document->setUser(null);
            }
        }

        return $this;
    }

     /**
     * Set deletedAt
     *
     * @param  \DateTime $deletedAt
     * @return Plan
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function addMedia(Media $media): self
    {
        if (!$this->medias->contains($media)) {
            $this->medias[] = $media;
            $media->setUser($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): self
    {
        if ($this->medias->removeElement($media)) {
            // set the owning side to null (unless already changed)
            if ($media->getUser() === $this) {
                $media->setUser(null);
            }
        }

        return $this;
    }

    public function getRedeem(): ?CodeRedeem
    {
        return $this->redeem;
    }

    public function setRedeem(?CodeRedeem $redeem): self
    {
        $this->redeem = $redeem;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

}