<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'genre')]
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
