<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\LibrarianRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: LibrarianRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'get:item:librarian']
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:librarian']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:librarian'],
            denormalizationContext: ['groups' => 'post:collection:librarian']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:librarian'],
            denormalizationContext: ['groups' => 'patch:item:librarian']
        ),
        new Delete(),
    ],
)]
class Librarian implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:librarian', 'get:collection:librarian'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups([
        'get:item:librarian',
        'get:collection:librarian',
        'post:collection:librarian',
        'patch:item:librarian'
    ])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Email]
    #[Assert\Length(max: 255)]
    #[Groups([
        'get:item:librarian',
        'get:collection:librarian',
        'post:collection:librarian',
        'patch:item:librarian'
    ])]
    private ?string $email = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Assert\Length(max: 15)]
    #[Assert\Regex(pattern: "/^\+?[0-9]{10,15}$/", message: "Телефонний номер повинен бути вірним")]
    #[Groups([
        'get:item:librarian',
        'get:collection:librarian',
        'post:collection:librarian',
        'patch:item:librarian'
    ])]
    private ?string $phone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Date]
    #[Assert\LessThanOrEqual("today")]
    #[Groups([
        'get:item:librarian',
        'get:collection:librarian',
        'post:collection:librarian',
        'patch:item:librarian'
    ])]
    private ?\DateTimeInterface $hire_date = null;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getHireDate(): ?\DateTimeInterface
    {
        return $this->hire_date;
    }

    public function setHireDate(?\DateTimeInterface $hire_date): static
    {
        $this->hire_date = $hire_date;

        return $this;
    }

    #[ArrayShape(['id' => "int|null", 'name' => "null|string", 'email' => "null|string", 'phone' => "null|string", 'hire_date' => "string"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'hire_date' => $this->getHireDate()->format('Y-m-d'),
        ];
    }
}
