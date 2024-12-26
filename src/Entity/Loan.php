<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\LoanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;


#[ORM\Entity(repositoryClass: LoanRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'get:item:loan']
        ),
        new GetCollection(
            normalizationContext: ['groups' => 'get:collection:loan']
        ),
        new Post(
            normalizationContext: ['groups' => 'get:item:loan'],
            denormalizationContext: ['groups' => 'post:collection:loan']
        ),
        new Patch(
            normalizationContext: ['groups' => 'get:item:loan'],
            denormalizationContext: ['groups' => 'patch:item:loan']
        ),
        new Delete(),
    ],
)]
class Loan implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get:item:loan', 'get:collection:loan'])]
    private ?int $id = null;

    /**
     * @var Collection<int, Reader>
     */

    #[ORM\OneToMany(targetEntity: Reader::class, mappedBy: 'Loan')]
    #[Assert\NotNull]
    #[Assert\Count(min: 1)]
    #[Groups(['get:item:loan'])]
    private Collection $reader;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\OneToMany(targetEntity: Book::class, mappedBy: 'Loan')]
    #[Assert\NotNull]
    #[Assert\Count(min: 1)]
    #[Groups(['get:item:loan'])]
    private Collection $book;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Date]
    #[Assert\LessThanOrEqual("today")]
    #[Groups([
        'get:item:loan',
        'get:collection:loan',
        'post:collection:loan',
        'patch:item:loan'
    ])]
    private ?\DateTimeInterface $loan_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Date]
    #[Assert\GreaterThanOrEqual(propertyPath: "loan_date")]
    #[Groups([
        'get:item:loan',
        'get:collection:loan',
        'post:collection:loan',
        'patch:item:loan'
    ])]
    private ?\DateTimeInterface $due_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Date]
    #[Assert\LessThanOrEqual("today")]
    #[Assert\GreaterThanOrEqual(propertyPath: "loan_date")]
    #[Groups([
        'get:item:loan',
        'get:collection:loan',
        'post:collection:loan',
        'patch:item:loan'
    ])]
    private ?\DateTimeInterface $return_date = null;

    public function __construct()
    {
        $this->reader = new ArrayCollection();
        $this->book = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Reader>
     */
    public function getReader(): Collection
    {
        return $this->reader;
    }

    public function addReader(Reader $reader): static
    {
        if (!$this->reader->contains($reader)) {
            $this->reader->add($reader);
            $reader->setLoan($this);
        }

        return $this;
    }

    public function removeReader(Reader $reader): static
    {
        if ($this->reader->removeElement($reader)) {
            // set the owning side to null (unless already changed)
            if ($reader->getLoan() === $this) {
                $reader->setLoan(null);
            }
        }

        return $this;
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
            $book->setLoan($this);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->book->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getLoan() === $this) {
                $book->setLoan(null);
            }
        }

        return $this;
    }

    public function getLoanDate(): ?\DateTimeInterface
    {
        return $this->loan_date;
    }

    public function setLoanDate(?\DateTimeInterface $loan_date): static
    {
        $this->loan_date = $loan_date;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(?\DateTimeInterface $due_date): static
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->return_date;
    }

    public function setReturnDate(?\DateTimeInterface $return_date): static
    {
        $this->return_date = $return_date;

        return $this;
    }

    #[ArrayShape(['id' => "int|null", 'loan_date' => "\DateTimeInterface|null", 'due_date' => "\DateTimeInterface|null", 'return_date' => "\DateTimeInterface|null", 'reader' => "\Doctrine\Common\Collections\Collection", 'book' => "\Doctrine\Common\Collections\Collection"])] public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'loan_date' => $this->getLoanDate(),
            'due_date' => $this->getDueDate(),
            'return_date' => $this->getReturnDate(),
            'reader' => $this->getReader(),
            'book' => $this->getBook(),
        ];
    }
}
