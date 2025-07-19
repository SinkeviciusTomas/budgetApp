<?php
// src/Form/Model/TransactionInput.php
namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class TransactionInput
{
    #[Assert\NotBlank]
    public string $mainType;

    #[Assert\NotBlank]
    #[Assert\Type('float')]
    public float $amount;

    public ?string $description = null;

    #[Assert\NotBlank]
    public string $category;

    #[Assert\NotBlank]
    public \DateTime $date;
}
