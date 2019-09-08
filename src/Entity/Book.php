<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 */
class Book
{
    public const BOOK_FILE = 'book.pdf';
    public const BOOK_COVER = 'cover';
    
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
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $downloadable;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $file;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $cover;

    // set default values
    public function __construct() {
        $this->downloadable = false;
        $this->file = false;
        $this->cover = false;
    }
    // build book public directory according to its' Id, like /0-10/1/
    public function getBookDir()
    {
        $folder = (int) floor($this->getId() / 10) * 10;
        $path = '/' . $folder . '-' . ($folder + 10) . '/' . $this->getId();

        return $path;
    }

    public function getFilePath()
    {
        return $this->getBookDir() . '/' . Book::BOOK_FILE;
    }
    
    public function getCoverPath()
    {
        return $this->getBookDir() . '/' . Book::BOOK_COVER;
    }

    public function getFile(): bool
    {
        return $this->file;
    }

    public function setFile(bool $file)
    {
        $this->file = $file;

        return $this;
    }

    
    public function getCover(): bool
    {
        return $this->cover;
    }

    public function setCover(bool $cover)
    {
        $this->cover = $cover;

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

    public function getDownloadable(): bool
    {
        return $this->downloadable;
    }

    public function setDownloadable(bool $downloadable): self
    {
        $this->downloadable = $downloadable;

        return $this;
    }

}
