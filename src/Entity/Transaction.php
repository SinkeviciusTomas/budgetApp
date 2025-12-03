<?php

namespace App\Entity;

use App\Enum\MainType;
use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\Table(name: '`transaction`')]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: MainType::class)]
    #[Assert\NotBlank(message: 'Please choose the transaction type')]
    #[SerializedName('transaction type')]
    private ?MainType $mainType = null;

    #[ORM\Column(length: 50)]
    #[Assert\Type('string')]
    #[Assert\NotBlank(message: 'Please choose the category')]
    private ?string $category = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Please input amount')]
    #[Assert\Positive(message: 'The amount cannot be negative or 0.')]
    #[Assert\Type('float')]
    private ?float $amount = null;

    #[ORM\Column(length: 128, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(min: 0, max: 128, maxMessage: 'The description can not be more than 128 characters')]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMainType(): ?MainType
    {
        return $this->mainType;
    }

    public function setMainType(MainType $mainType): self
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
}
