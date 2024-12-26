<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\BranchRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: BranchRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'get:item:branch']
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:branch']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:branch'],
            denormalizationContext: ['groups' => 'post:collection:branch']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:branch'],
            denormalizationContext: ['groups' => 'patch:item:branch']
        ),
        new Delete(),
    ],
)]
class Branch implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:branch', 'get:collection:branch'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups([
        'get:item:branch',
        'get:collection:branch',
        'post:collection:branch',
        'patch:item:branch'
    ])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups([
        'get:item:branch',
        'get:collection:branch',
        'post:collection:branch',
        'patch:item:branch'
    ])]
    private ?string $address = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Assert\Length(max: 15)]
    #[Assert\Regex(pattern: "/^\+?[0-9]{10,15}$/", message: "Телефонний номер повинен бути вірним")]
    #[Groups([
        'get:item:branch',
        'get:collection:branch',
        'post:collection:branch',
        'patch:item:branch'
    ])]
    private ?string $phone = null;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

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

    #[ArrayShape(['id' => "int|null", 'name' => "null|string", 'address' => "null|string", 'phone' => "null|string"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'address' => $this->getAddress(),
            'phone' => $this->getPhone(),
        ];
    }
}
