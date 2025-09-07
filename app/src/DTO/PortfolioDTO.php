<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Data Transfer Object for Portfolio.
 */
class PortfolioDTO
{
    #[Assert\NotBlank(message: 'portfolio.name.not_blank')]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'portfolio.name.min_length',
        maxMessage: 'portfolio.name.max_length'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s\-_]+$/',
        message: 'portfolio.name.regex'
    )]
    public ?string $name = null;

    #[Assert\Type(type: 'string', message: 'portfolio.description.type')]
    #[Assert\Length(max: 500, maxMessage: 'portfolio.description.max_length')]
    public ?string $description = null;

    #[Assert\Type(type: 'numeric', message: 'portfolio.initial_balance.type')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'portfolio.initial_balance.greater_than_or_equal_zero')]
    public ?float $initialBalance = null;

    #[Assert\Type(type: 'string', message: 'portfolio.currency.type')]
    #[Assert\Length(min: 3, max: 3, exactMessage: 'portfolio.currency.exact_length')]
    #[Assert\Regex(
        pattern: '/^[A-Z]{3}$/',
        message: 'portfolio.currency.regex'
    )]
    public ?string $currency = null;

    public function __construct(
        ?string $name = null,
        ?string $description = null,
        ?float $initialBalance = 0.0,
        ?string $currency = 'PLN'
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->initialBalance = $initialBalance;
        $this->currency = $currency;
    }
}
