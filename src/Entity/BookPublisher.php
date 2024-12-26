<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\BookPublisherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: BookPublisherRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'get:item:bookPublisher']
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:bookPublisher']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:bookPublisher'],
            denormalizationContext: ['groups' => 'post:collection:bookPublisher']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:bookPublisher'],
            denormalizationContext: ['groups' => 'patch:item:bookPublisher']
        ),
        new Delete(),
    ],
)]

class BookPublisher implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:bookPublisher', 'get:collection:bookPublisher'])]
    private ?int $id = null;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\OneToMany(targetEntity: Book::class, mappedBy: 'BookPublisher')]
    #[Assert\NotNull]
    #[Assert\Count(min: 1)]
    #[Groups([
        'get:item:bookPublisher',
        'get:collection:bookPublisher',
        'post:collection:bookPublisher',
        'patch:item:bookPublisher'
    ])]
    private Collection $book;

    /**
     * @var Collection<int, Publisher>
     */
    #[ORM\ManyToMany(targetEntity: Publisher::class, inversedBy: 'BookPublisher')]
    #[Assert\NotNull]
    #[Assert\Count(min: 1)]
    #[Groups(['get:item:bookPublisher', 'get:collection:bookPublisher'])]
    private Collection $publisher;

    public function __construct()
    {
        $this->book = new ArrayCollection();
        $this->publisher = new ArrayCollection();
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
            $book->setBookPublisher($this);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->book->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getBookPublisher() === $this) {
                $book->setBookPublisher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Publisher>
     */
    public function getPublisher(): Collection
    {
        return $this->publisher;
    }

    public function addPublisher(Publisher $publisher): static
    {
        if (!$this->publisher->contains($publisher)) {
            $this->publisher->add($publisher);
        }

        return $this;
    }

    public function removePublisher(Publisher $publisher): static
    {
        $this->publisher->removeElement($publisher);

        return $this;
    }

    #[ArrayShape(['id' => "int|null", 'book' => "\Doctrine\Common\Collections\Collection", 'publisher' => "\Doctrine\Common\Collections\Collection"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'book' => $this->getBook(),
            'publisher' => $this->getPublisher(),
        ];
    }
}
