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
     */
    public function entityToDto(User $user): UserDTO
    {
        $dto = new UserDTO();
        $dto->email = $user->getEmail();
        $dto->firstName = $user->getFirstName();
        $dto->lastName = $user->getLastName();
        $dto->phone = $user->getPhone();

        return $dto;
    }

    /**
     * Convert UserDTO to User entity.
     */
    public function dtoToEntity(UserDTO $dto, ?User $user = null): User
    {
        if (null === $user) {
            $user = new User();
        }

        $user->setEmail($dto->email);
        $user->setFirstName($dto->firstName);
        $user->setLastName($dto->lastName);
        $user->setPhone($dto->phone);

        return $user;
    }

    /**
     * Update User entity with data from UserDTO.
     */
    public function updateEntityFromDto(User $user, UserDTO $dto): User
    {
        return $this->dtoToEntity($dto, $user);
    }
}
