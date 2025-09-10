<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Data Transfer Object for Tag.
 */
class TagDTO
{
    #[Assert\NotBlank(message: 'tag.name.not_blank')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'tag.name.min_length',
        maxMessage: 'tag.name.max_length'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s\-_]+$/',
        message: 'tag.name.regex'
    )]
    public ?string $name = null;

    #[Assert\Type(type: 'string', message: 'tag.color.type')]
    #[Assert\Regex(
        pattern: '/^#[0-9A-Fa-f]{6}$/',
        message: 'tag.color.regex'
    )]
    public ?string $color = null;

    /**
     * Constructor.
     *
     * @param string|null $name  the name
     * @param string|null $color the color
     */
    public function __construct(?string $name = null, ?string $color = null)
    {
        $this->name = $name;
        $this->color = $color ?? '#007bff';
    }
}
