<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Data Transfer Object for changing user password.
 */
class ChangePasswordDTO
{
    #[Assert\NotBlank(message: 'user.current_password.not_blank')]
    #[Assert\Type(type: 'string', message: 'user.current_password.type')]
    public ?string $currentPassword = null;

    #[Assert\NotBlank(message: 'user.new_password.not_blank')]
    #[Assert\Type(type: 'string', message: 'user.new_password.type')]
    #[Assert\Length(
        min: 8,
        max: 255,
        minMessage: 'user.new_password.min_length',
        maxMessage: 'user.new_password.max_length'
    )]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
        message: 'user.new_password.regex'
    )]
    public ?string $newPassword = null;

    #[Assert\NotBlank(message: 'user.confirm_password.not_blank')]
    #[Assert\Type(type: 'string', message: 'user.confirm_password.type')]
    #[Assert\EqualTo(
        propertyPath: 'newPassword',
        message: 'user.confirm_password.equal_to'
    )]
    public ?string $confirmPassword = null;

    public function __construct(
        ?string $currentPassword = null,
        ?string $newPassword = null,
        ?string $confirmPassword = null
    ) {
        $this->currentPassword = $currentPassword;
        $this->newPassword = $newPassword;
        $this->confirmPassword = $confirmPassword;
    }
}
