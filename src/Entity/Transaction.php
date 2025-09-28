<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\Table(name: '`transaction`')]
class Transaction
{

    // TODO: Add validators to entities
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $mainType = null;

    #[ORM\Column(length: 50)]
    private ?string $category = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Assert\Type("integer")]
    private ?float $amount = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $date = null;

    #[ORM\Column(length: 20)]
    private ?string $sourceTable = null;

    #[ORM\Column]
    private ?int $sourceId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMainType(): ?string
    {
        return $this->mainType;
    }

    public function setMainType(string $mainType): static
    {
        $this->mainType = $mainType;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getSourceTable(): ?string
    {
        return $this->sourceTable;
    }

    public function setSourceTable(string $sourceTable): static
    {
        $this->sourceTable = $sourceTable;

        return $this;
    }

    public function getSourceId(): ?int
    {
        return $this->sourceId;
    }

    public function setSourceId(int $sourceId): static
    {
        $this->sourceId = $sourceId;

        return $this;
    }
}
