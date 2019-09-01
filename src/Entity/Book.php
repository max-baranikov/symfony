<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="date")
     */
    private $last_read;

    /**
     * @ORM\Column(type="boolean")
     */
    private $downloadable;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $author_id;

   /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $bookFilename;

    public function getBookFilename(): ?string
    {
        return $this->bookFilename;
    }

    public function setBookFilename(?string $bookFilename): self
    {
        $this->bookFilename = $bookFilename;

        return $this;
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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getLastRead(): ?\DateTimeInterface
    {
        return $this->last_read;
    }

    public function setLastRead(\DateTimeInterface $last_read): self
    {
        $this->last_read = $last_read;

        return $this;
    }

    public function getDownloadable(): ?bool
    {
        return $this->downloadable;
    }

    public function setDownloadable(bool $downloadable): self
    {
        $this->downloadable = $downloadable;

        return $this;
    }

    public function getAuthorId(): ?int
    {
        return $this->author_id;
    }

    public function setAuthorId(?int $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }
}
