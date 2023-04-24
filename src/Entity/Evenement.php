<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
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
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=comment::class)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=Work::class)
     */
    private $hiring;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $edate;

    /**
     * @ORM\ManyToOne(targetEntity=Purpose::class)
     */
    private $purpose;

    /**
     * @ORM\ManyToOne(targetEntity=coderedeem::class)
     */
    private $coupon;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class)
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity=Experience::class)
     */
    private $experience;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;

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

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getHiring(): ?Work
    {
        return $this->hiring;
    }

    public function setHiring(?Work $hiring): self
    {
        $this->hiring = $hiring;

        return $this;
    }

    public function getEdate(): ?\DateTimeInterface
    {
        return $this->edate;
    }

    public function setEdate(?\DateTimeInterface $edate): self
    {
        $this->edate = $edate;

        return $this;
    }

    public function getPurpose(): ?purpose
    {
        return $this->purpose;
    }

    public function setPurpose(?purpose $purpose): self
    {
        $this->purpose = $purpose;

        return $this;
    }

    public function getCoupon(): ?coderedeem
    {
        return $this->coupon;
    }

    public function setCoupon(?coderedeem $coupon): self
    {
        $this->coupon = $coupon;

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

    public function getExperience(): ?Experience
    {
        return $this->experience;
    }

    public function setExperience(?Experience $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

 


}
