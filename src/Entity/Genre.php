<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\GenreRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'get:item:genre']
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:genre']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:genre'],
            denormalizationContext: ['groups' => 'post:collection:genre']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:genre'],
            denormalizationContext: ['groups' => 'patch:item:genre']
        ),
        new Delete(),
    ],
)]
class Genre implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:genre', 'get:collection:genre'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups([
        'get:item:genre',
        'get:collection:genre',
        'post:collection:genre',
        'patch:item:genre'
    ])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'genre')]
    #[Assert\NotNull]
    #[Groups([
        'get:item:genre',
        'get:collection:genre',
        'post:collection:genre',
        'patch:item:genre'
    ])]
    private ?BookGenre $bookGenre = null;

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

    public function getBookGenre(): ?BookGenre
    {
        return $this->bookGenre;
    }

    public function setBookGenre(?BookGenre $bookGenre): static
    {
        $this->bookGenre = $bookGenre;

        return $this;
    }

    #[ArrayShape(['id' => "int|null", 'name' => "null|string", 'bookGenre' => "\App\Entity\BookGenre|null"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'bookGenre' => $this->getBookGenre(),
        ];
    }
}
