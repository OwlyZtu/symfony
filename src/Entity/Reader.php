<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\ReaderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ReaderRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'get:item:reader']
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:reader']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:reader'],
            denormalizationContext: ['groups' => 'post:collection:reader']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:reader'],
            denormalizationContext: ['groups' => 'patch:item:reader']
        ),
        new Delete(),
    ],
)]
class Reader implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:reader', 'get:collection:reader'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups([
        'get:item:reader',
        'get:collection:reader',
        'post:collection:reader',
        'patch:item:reader'
    ])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Email]
    #[Assert\Length(max: 255)]
    #[Groups([
        'get:item:reader',
        'get:collection:reader',
        'post:collection:reader',
        'patch:item:reader'
    ])]
    private ?string $email = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Assert\Length(max: 15)]
    #[Assert\Regex(pattern: "/^\+?[0-9]{10,15}$/", message: "Телефонний номер повинен бути вірним")]
    #[Groups([
        'get:item:reader',
        'get:collection:reader',
        'post:collection:reader',
        'patch:item:reader'
    ])]
    private ?string $phone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Date]
    #[Assert\LessThanOrEqual("today")]
    #[Groups([
        'get:item:reader',
        'get:collection:reader',
        'post:collection:reader',
        'patch:item:reader'
    ])]
    private ?\DateTimeInterface $registration_date = null;

    #[ORM\ManyToOne(inversedBy: 'reader')]
    #[Groups([
        'get:item:reader',
        'get:collection:reader',
        'post:collection:reader',
        'patch:item:reader'
    ])]
    private ?Loan $loans = null;

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

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registration_date;
    }

    public function setRegistrationDate(?\DateTimeInterface $registration_date): static
    {
        $this->registration_date = $registration_date;

        return $this;
    }

    public function getLoan(): ?Loan
    {
        return $this->loans;
    }

    public function setLoan(?Loan $loan): static
    {
        $this->loans = $loan;

        return $this;
    }

    #[ArrayShape(['id' => "int|null", 'name' => "null|string", 'email' => "null|string", 'phone' => "null|string", 'registration_date' => "string", 'loans' => "\App\Entity\Loan|null"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'registration_date' => $this->getRegistrationDate()->format('Y-m-d'),
            'loans' => $this->getLoan(),
        ];
    }
}
