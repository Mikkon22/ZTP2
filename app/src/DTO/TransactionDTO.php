<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\DTO;

use App\Entity\Category;
use App\Entity\Portfolio;
use App\Entity\Tag;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Data Transfer Object for Transaction.
 */
class TransactionDTO
{
    #[Assert\NotBlank(message: 'transaction.title.not_blank')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'transaction.title.min_length',
        maxMessage: 'transaction.title.max_length'
    )]
    public ?string $title = null;

    #[Assert\NotBlank(message: 'transaction.amount.not_blank')]
    #[Assert\Type(type: 'numeric', message: 'transaction.amount.type')]
    #[Assert\GreaterThan(value: 0, message: 'transaction.amount.greater_than_zero')]
    public ?float $amount = null;

    #[Assert\NotBlank(message: 'transaction.date.not_blank')]
    #[Assert\Type(type: '\DateTimeInterface', message: 'transaction.date.type')]
    public ?\DateTimeInterface $date = null;

    #[Assert\NotBlank(message: 'transaction.portfolio.not_blank')]
    #[Assert\Type(type: Portfolio::class, message: 'transaction.portfolio.type')]
    public ?Portfolio $portfolio = null;

    #[Assert\NotBlank(message: 'transaction.category.not_blank')]
    #[Assert\Type(type: Category::class, message: 'transaction.category.type')]
    public ?Category $category = null;

    #[Assert\Type(type: 'string', message: 'transaction.description.type')]
    #[Assert\Length(max: 1000, maxMessage: 'transaction.description.max_length')]
    public ?string $description = null;

    #[Assert\Type(type: 'array', message: 'transaction.tags.type')]
    #[Assert\All([
        new Assert\Type(type: Tag::class, message: 'transaction.tags.all_type'),
    ])]
    public array $tags = [];

    #[Assert\NotBlank(message: 'transaction.transaction_type.not_blank')]
    #[Assert\Choice(choices: ['income', 'expense'], message: 'transaction.transaction_type.choice')]
    public ?string $transactionType = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * Get the amount with proper sign based on transaction type.
     *
     * @return float|null the signed amount
     */
    public function getSignedAmount(): ?float
    {
        if (null === $this->amount || null === $this->transactionType) {
            return null;
        }

        return 'expense' === $this->transactionType ? -abs($this->amount) : abs($this->amount);
    }

    /**
     * Set amount with proper sign based on transaction type.
     *
     * @param float $amount the amount to set
     */
    public function setSignedAmount(float $amount): void
    {
        $this->amount = abs($amount);
        $this->transactionType = $amount < 0 ? 'expense' : 'income';
    }
}
