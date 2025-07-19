<?php

namespace App\Entity;

use App\Repository\ExpenseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ExpenseRepository::class)]
class Expense
{
    // TODO: Add validators to entities
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Assert\Type("integer")]
    private ?float $amount = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $category = null;

    #[ORM\Column]
    private ?\DateTime $date = null;

    #[ORM\Column(length: 20)]
    private ?string $mainType = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

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

    public function getMainType(): ?string
    {
        return $this->mainType;
    }

    public function setMainType(string $mainType): static
    {
        $this->mainType = $mainType;

        return $this;
    }
}
