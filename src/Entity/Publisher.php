<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\PublisherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PublisherRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'get:item:publisher']
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:publisher']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:publisher'],
            denormalizationContext: ['groups' => 'post:collection:publisher']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:publisher'],
            denormalizationContext: ['groups' => 'patch:item:publisher']
        ),
        new Delete(),
    ],
)]
class Publisher implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:publisher', 'get:collection:publisher'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups([
        'get:item:publisher',
        'get:collection:publisher',
        'post:collection:publisher',
        'patch:item:publisher'
    ])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups([
        'get:item:publisher',
        'get:collection:publisher',
        'post:collection:publisher',
        'patch:item:publisher'
    ])]
    private ?string $address = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Assert\Length(max: 15)]
    #[Assert\Regex(pattern: "/^\+?[0-9]{10,15}$/", message: "Телефонний номер повинен бути вірним")]
    #[Groups([
        'get:item:publisher',
        'get:collection:publisher',
        'post:collection:publisher',
        'patch:item:publisher'
    ])]
    private ?string $phone = null;

    /**
     * @var Collection<int, BookPublisher>
     */
    #[ORM\ManyToMany(targetEntity: BookPublisher::class, mappedBy: 'publisher')]
    #[Assert\NotNull]
    #[Assert\Count(min: 1)]
    #[Groups(['get:item:publisher', 'get:collection:publisher'])]
    private Collection $bookPublisher;

    public function __construct()
    {
        $this->bookPublisher = new ArrayCollection();
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

    /**
     * @return Collection<int, BookPublisher>
     */
    public function getBookPublisher(): Collection
    {
        return $this->bookPublisher;
    }

    public function addBookPublisher(BookPublisher $bookPublisher): static
    {
        if (!$this->bookPublisher->contains($bookPublisher)) {
            $this->bookPublisher->add($bookPublisher);
            $bookPublisher->addPublisher($this);
        }

        return $this;
    }

    public function removeBookPublisher(BookPublisher $bookPublisher): static
    {
        if ($this->bookPublisher->removeElement($bookPublisher)) {
            $bookPublisher->removePublisher($this);
        }

        return $this;
    }

    #[ArrayShape(['id' => "int|null", 'name' => "null|string", 'address' => "null|string", 'phone' => "null|string", 'bookPublisher' => "\Doctrine\Common\Collections\Collection"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'address' => $this->getAddress(),
            'phone' => $this->getPhone(),
            'bookPublisher' => $this->getBookPublisher()
        ];
    }
}
