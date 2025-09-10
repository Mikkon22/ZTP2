<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Mapper;

use App\DTO\UserDTO;
use App\Entity\User;

/**
 * Mapper for converting between User entity and UserDTO.
 */
class UserMapper
{
    /**
     * Convert User entity to UserDTO.
     *
     * @param User $user the user entity
     *
     * @return UserDTO the user DTO
     */
    public function entityToDto(User $user): UserDTO
    {
        $dto = new UserDTO();
        $dto->email = $user->getEmail();
        $dto->firstName = $user->getFirstName();
        $dto->lastName = $user->getLastName();

        return $dto;
    }

    /**
     * Convert UserDTO to User entity.
     *
     * @param UserDTO   $dto  the user DTO
     * @param User|null $user the existing user entity
     *
     * @return User the user entity
     */
    public function dtoToEntity(UserDTO $dto, ?User $user = null): User
    {
        if (null === $user) {
            $user = new User();
        }

        $user->setEmail($dto->email);
        $user->setFirstName($dto->firstName);
        $user->setLastName($dto->lastName);

        return $user;
    }

    /**
     * Update User entity with data from UserDTO.
     *
     * @param User    $user the user entity
     * @param UserDTO $dto  the user DTO
     *
     * @return User the updated user entity
     */
    public function updateEntityFromDto(User $user, UserDTO $dto): User
    {
        return $this->dtoToEntity($dto, $user);
    }
}
