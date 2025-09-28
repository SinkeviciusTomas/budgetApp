<?php
// src/Form/Model/TransactionInput.php
namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class TransactionInput
{
    #[Assert\NotBlank]
    #[Assert\Type('float')]
    public float $amount;
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $mainType;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    public string $category;

    #[Assert\Type('string')]
    public ?string $description = null;

    #[Assert\NotBlank]
    #[Assert\Type(\DatetimeInterface::class)]
    public \DateTime $date;
}
