<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Action\Author\UpdateAuthorAction;
use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'get:item:author']
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:author']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:author'],
            denormalizationContext: ['groups' => 'post:collection:author']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:author'],
            denormalizationContext: ['groups' => 'patch:item:author']
        ),
        new Delete(),
        new Patch(
            uriTemplate: '/author/{id}/update-nationality',
            controller: UpdateAuthorAction::class,
            denormalizationContext: ['groups' => 'patch:item:author'],
            name: 'update_nationality'
        ),
    ],
)]
class Author implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:author', 'get:collection:author'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups([
        'get:item:author',
        'get:collection:author',
        'post:collection:author',
        'patch:item:author'
    ])]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Date]
    #[Assert\LessThanOrEqual("today")]
    #[Groups([
        'get:item:author',
        'get:collection:author',
        'post:collection:author',
        'patch:item:author'
    ])]
    private ?\DateTimeInterface $birth_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups([
        'get:item:author',
        'get:collection:author',
        'post:collection:author',
        'patch:item:author'
    ])]
    private ?string $nationality = null;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\OneToMany(targetEntity: Book::class, mappedBy: 'author')]
    #[Groups(['get:item:author'])]
    private Collection $Book;

    public function __construct()
    {
        $this->Book = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthDate(?\DateTimeInterface $birth_date): static
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): static
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBook(): Collection
    {
        return $this->Book;
    }

    public function addBook(Book $book): static
    {
        if (!$this->Book->contains($book)) {
            $this->Book->add($book);
            $book->setAuthor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->Book->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getAuthor() === $this) {
                $book->setAuthor(null);
            }
        }

        return $this;
    }

    #[ArrayShape(['id' => "int|null", 'name' => "null|string", 'birth_date' => "\DateTimeInterface|null", 'nationality' => "null|string"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'birth_date' => $this->getBirthDate()->format('Y-m-d'),
            'nationality' => $this->getNationality()
        ];
    }
}
