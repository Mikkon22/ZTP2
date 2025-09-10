<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Data Transfer Object for User.
 */
class UserDTO
{
    #[Assert\NotBlank(message: 'user.email.not_blank')]
    #[Assert\Email(message: 'user.email.email')]
    #[Assert\Length(max: 180, maxMessage: 'user.email.max_length')]
    public ?string $email = null;

    #[Assert\NotBlank(message: 'user.first_name.not_blank')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'user.first_name.min_length',
        maxMessage: 'user.first_name.max_length'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s\-]+$/',
        message: 'user.first_name.regex'
    )]
    public ?string $firstName = null;

    #[Assert\NotBlank(message: 'user.last_name.not_blank')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'user.last_name.min_length',
        maxMessage: 'user.last_name.max_length'
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s\-]+$/',
        message: 'user.last_name.regex'
    )]
    public ?string $lastName = null;

    #[Assert\Type(type: 'string', message: 'user.phone.type')]
    #[Assert\Regex(
        pattern: '/^(\+48\s?)?[0-9]{3}[\s\-]?[0-9]{3}[\s\-]?[0-9]{3}$/',
        message: 'user.phone.regex'
    )]
    public ?string $phone = null;

    /**
     * Constructor.
     *
     * @param string|null $email     the email
     * @param string|null $firstName the first name
     * @param string|null $lastName  the last name
     * @param string|null $phone     the phone
     */
    public function __construct(?string $email = null, ?string $firstName = null, ?string $lastName = null, ?string $phone = null)
    {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
    }

    /**
     * Get full name.
     *
     * @return string|null the full name
     */
    public function getFullName(): ?string
    {
        if (null === $this->firstName || null === $this->lastName) {
            return null;
        }

        return trim($this->firstName.' '.$this->lastName);
    }
}
