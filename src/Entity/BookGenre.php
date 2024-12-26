<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\BookGenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookGenreRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'get:item:bookGenre']
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:bookGenre']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:bookGenre'],
            denormalizationContext: ['groups' => 'post:collection:bookGenre']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:bookGenre'],
            denormalizationContext: ['groups' => 'patch:item:bookGenre']
        ),
        new Delete(),
    ],
)]
class BookGenre implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:bookGenre', 'get:collection:bookGenre'])]
    private ?int $id = null;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'BookGenre')]
    #[Assert\NotNull]
    #[Assert\Count(min: 1)]
    #[Groups(['get:item:bookGenre', 'get:collection:bookGenre'])]
    private Collection $book;

    /**
     * @var Collection<int, Genre>
     */
    #[ORM\OneToMany(targetEntity: Genre::class, mappedBy: 'BookGenre')]
    #[Assert\NotNull]
    #[Assert\Count(min: 1)]
    #[Groups(['get:item:bookGenre'])]
    private Collection $genre;

    public function __construct()
    {
        $this->book = new ArrayCollection();
        $this->genre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBook(): Collection
    {
        return $this->book;
    }

    public function addBook(Book $book): static
    {
        if (!$this->book->contains($book)) {
            $this->book->add($book);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        $this->book->removeElement($book);

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenre(): Collection
    {
        return $this->genre;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genre->contains($genre)) {
            $this->genre->add($genre);
            $genre->setBookGenre($this);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        if ($this->genre->removeElement($genre)) {
            // set the owning side to null (unless already changed)
            if ($genre->getBookGenre() === $this) {
                $genre->setBookGenre(null);
            }
        }

        return $this;
    }

    #[ArrayShape(['id' => "int|null", 'book' => "\Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection", 'genre' => "\Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'book' => $this->getBook(),
            'genre' => $this->getGenre(),
        ];
    }
}
