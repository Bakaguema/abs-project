<?php

namespace App\Entity;

use App\Repository\FAQRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FAQRepository::class)
 */
class FAQ
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
    private $question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $response;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkUrl;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
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

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(string $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getLinkName(): ?string
    {
        return $this->linkName;
    }

    public function setLinkName(?string $linkName): self
    {
        $this->linkName = $linkName;

        return $this;
    }

    public function getLinkUrl(): ?string
    {
        return $this->linkUrl;
    }

    public function setLinkUrl(?string $linkUrl): self
    {
        $this->linkUrl = $linkUrl;

        return $this;
    }
}
