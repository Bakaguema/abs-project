<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Chat::class, inversedBy="messages", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"messagerie", "messagerieNotif"})
     */
    private $chat;

    /**
     * @ORM\Column(type="string", length=500)
     * @Groups("messagerie")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups("messagerie")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $readAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"messagerie", "messagerieNotif"})
     */
    private $from_id;

    /**
     * @ORM\Column(type="json")
     * @Groups({"messagerie", "messagerieNotif"})
     */
    private $to_id = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @ORM\OneToMany(targetEntity=Attachments::class, mappedBy="message", orphanRemoval=true, cascade={"persist"})
     * @Groups("messagerie")
     */
    private $attachments;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    public function setChat(?Chat $chat): self
    {
        $this->chat = $chat;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getReadAt(): ?\DateTimeImmutable
    {
        return $this->readAt;
    }

    public function setReadAt(?\DateTimeImmutable $readAt): self
    {
        $this->readAt = $readAt;

        return $this;
    }

    public function getFromId(): ?int
    {
        return $this->from_id;
    }

    public function setFromId(?int $from_id): self
    {
        $this->from_id = $from_id;

        return $this;
    }

    public function getToId(): ?array
    {
        return $this->to_id;
    }

    public function setToId(array $to_id): self
    {
        $this->to_id = $to_id;

        return $this;
    }

    /**
    * @ORM\PrePersist
    * @ORM\PreUpdate 
    */
    public function updateTimestamps(): void
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new DateTimeImmutable());
        }
        $this->setUpdatedAt(new DateTimeImmutable());
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return Collection<int, Attachments>
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachments $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments[] = $attachment;
            $attachment->setMessage($this);
        }

        return $this;
    }

    public function removeAttachment(Attachments $attachment): self
    {
        if ($this->attachments->removeElement($attachment)) {
            // set the owning side to null (unless already changed)
            if ($attachment->getMessage() === $this) {
                $attachment->setMessage(null);
            }
        }

        return $this;
    }
}