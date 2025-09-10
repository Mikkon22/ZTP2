<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Mapper;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Mapper\UserMapper;
use PHPUnit\Framework\TestCase;

/**
 * Test class for UserMapper.
 */
class UserMapperTest extends TestCase
{
    private UserMapper $mapper;

    /**
     * Set up test.
     */
    protected function setUp(): void
    {
        $this->mapper = new UserMapper();
    }

    /**
     * Test entity to DTO conversion.
     */
    public function testEntityToDto(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setFirstName('John');
        $user->setLastName('Doe');

        $dto = $this->mapper->entityToDto($user);

        $this->assertInstanceOf(UserDTO::class, $dto);
        $this->assertEquals('test@example.com', $dto->email);
        $this->assertEquals('John', $dto->firstName);
        $this->assertEquals('Doe', $dto->lastName);
    }

    /**
     * Test DTO to entity conversion with new entity.
     */
    public function testDtoToEntityWithNewEntity(): void
    {
        $dto = new UserDTO();
        $dto->email = 'new@example.com';
        $dto->firstName = 'Jane';
        $dto->lastName = 'Smith';

        $user = $this->mapper->dtoToEntity($dto);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('new@example.com', $user->getEmail());
        $this->assertEquals('Jane', $user->getFirstName());
        $this->assertEquals('Smith', $user->getLastName());
    }

    /**
     * Test update entity from DTO.
     */
    public function testUpdateEntityFromDto(): void
    {
        $user = new User();
        $user->setEmail('old@example.com');
        $user->setFirstName('Old');
        $user->setLastName('Name');

        $dto = new UserDTO();
        $dto->email = 'updated@example.com';
        $dto->firstName = 'Updated';
        $dto->lastName = 'Name';

        $this->mapper->updateEntityFromDto($user, $dto);

        $this->assertEquals('updated@example.com', $user->getEmail());
        $this->assertEquals('Updated', $user->getFirstName());
        $this->assertEquals('Name', $user->getLastName());
    }
}
