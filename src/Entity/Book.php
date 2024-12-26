<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Action\Book\UpdateBookQuantityAction;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'get:item:book']
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:book']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:book'],
            denormalizationContext: ['groups' => 'post:collection:book']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:book'],
            denormalizationContext: ['groups' => 'patch:item:book']
        ),
        new Delete(),
        new Patch(
            uriTemplate: '/book/{id}/update-book-quantity',
            controller: UpdateBookQuantityAction::class,
            denormalizationContext: ['groups' => 'patch:item:book'],
            name: 'update_book_quantity'
        ),
    ],
)]
class Book implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:book', 'get:collection:book'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups([
        'get:item:book',
        'get:collection:book',
        'post:collection:book',
        'patch:item:book'
    ])]
    private ?string $title = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type(type: "integer")]
    #[Groups([
        'get:item:book',
        'get:collection:book',
        'post:collection:book',
        'patch:item:book'
    ])]
    private ?int $author_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups([
        'get:item:book',
        'get:collection:book',
        'post:collection:book',
        'patch:item:book'
    ])]
    private ?string $genre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Date]
    #[Assert\LessThanOrEqual("today")]
    #[Groups([
        'get:item:book',
        'get:collection:book',
        'post:collection:book',
        'patch:item:book'
    ])]
    private ?\DateTimeInterface $publish_year = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type(type: "integer")]
    #[Assert\Positive]
    #[Groups([
        'get:item:book',
        'get:collection:book',
        'post:collection:book',
        'patch:item:book'
    ])]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'Book')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    #[Groups([
        'get:item:book',
        'get:collection:book',
        'post:collection:book',
        'patch:item:book'
    ])]
    private ?Author $author = null;

    #[ORM\ManyToOne(inversedBy: 'book')]
    #[Groups([
        'get:item:book',
        'get:collection:book',
        'post:collection:book',
        'patch:item:book'
    ])]
    private ?Loan $Loan = null;

    #[ORM\ManyToOne(inversedBy: 'book')]
    #[Groups([
        'get:item:book',
        'get:collection:book',
        'post:collection:book',
        'patch:item:book'
    ])]
    private ?BookPublisher $BookPublisher = null;

    /**
     * @var Collection<int, BookGenre>
     */
    #[ORM\ManyToMany(targetEntity: BookGenre::class, mappedBy: 'book')]
    private Collection $BookGenre;

    public function __construct()
    {
        $this->BookGenre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthorId(): ?int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): static
    {
        $this->author_id = $author_id;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getPublishYear(): ?\DateTimeInterface
    {
        return $this->publish_year;
    }

    public function setPublishYear(?\DateTimeInterface $publish_year): static
    {
        $this->publish_year = $publish_year;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getLoan(): ?Loan
    {
        return $this->Loan;
    }

    public function setLoan(?Loan $Loan): static
    {
        $this->Loan = $Loan;

        return $this;
    }

    public function getBookPublisher(): ?BookPublisher
    {
        return $this->BookPublisher;
    }

    public function setBookPublisher(?BookPublisher $BookPublisher): static
    {
        $this->BookPublisher = $BookPublisher;

        return $this;
    }

    /**
     * @return Collection<int, BookGenre>
     */
    public function getBookGenre(): Collection
    {
        return $this->BookGenre;
    }

    public function addBookGenre(BookGenre $bookGenre): static
    {
        if (!$this->BookGenre->contains($bookGenre)) {
            $this->BookGenre->add($bookGenre);
            $bookGenre->addBook($this);
        }

        return $this;
    }

    public function removeBookGenre(BookGenre $bookGenre): static
    {
        if ($this->BookGenre->removeElement($bookGenre)) {
            $bookGenre->removeBook($this);
        }

        return $this;
    }

    #[ArrayShape(['id' => "int|null", 'title' => "null|string", 'author' => "\App\Entity\Author|null", 'genre' => "null|string", 'publish_year' => "\DateTimeInterface|null", 'quantity' => "int|null"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'author' => $this->getAuthor(),
            'genre' => $this->getGenre(),
            'publish_year' => $this->getPublishYear()->format('Y'),
            'quantity' => $this->getQuantity()
        ];
    }
}
