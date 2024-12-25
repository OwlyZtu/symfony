<?php

namespace App\Entity;

use App\Repository\BranchRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BranchRepository::class)]
class Branch implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Assert\Length(max: 15)]
    #[Assert\Regex(pattern: "/^\+?[0-9]{10,15}$/", message: "Телефонний номер повинен бути вірним")]
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
