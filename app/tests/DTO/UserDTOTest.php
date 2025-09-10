<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\DTO;

use App\DTO\UserDTO;
use PHPUnit\Framework\TestCase;

/**
 * Test class for UserDTO.
 */
class UserDTOTest extends TestCase
{
    /**
     * Test constructor with default values.
     */
    public function testConstructorWithDefaultValues(): void
    {
        $dto = new UserDTO();

        $this->assertNull($dto->email);
        $this->assertNull($dto->firstName);
        $this->assertNull($dto->lastName);
        $this->assertNull($dto->phone);
    }

    /**
     * Test constructor with values.
     */
    public function testConstructorWithValues(): void
    {
        $dto = new UserDTO('test@example.com', 'John', 'Doe', '123456789');

        $this->assertEquals('test@example.com', $dto->email);
        $this->assertEquals('John', $dto->firstName);
        $this->assertEquals('Doe', $dto->lastName);
        $this->assertEquals('123456789', $dto->phone);
    }

    /**
     * Test setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        $dto = new UserDTO();

        $dto->email = 'updated@example.com';
        $dto->firstName = 'Jane';
        $dto->lastName = 'Smith';
        $dto->phone = '987654321';

        $this->assertEquals('updated@example.com', $dto->email);
        $this->assertEquals('Jane', $dto->firstName);
        $this->assertEquals('Smith', $dto->lastName);
        $this->assertEquals('987654321', $dto->phone);
    }

    /**
     * Test get full name with both names.
     */
    public function testGetFullNameWithBothNames(): void
    {
        $dto = new UserDTO('test@example.com', 'John', 'Doe');

        $this->assertEquals('John Doe', $dto->getFullName());
    }

    /**
     * Test get full name with only first name.
     */
    public function testGetFullNameWithOnlyFirstName(): void
    {
        $dto = new UserDTO('test@example.com', 'John', null);

        $this->assertNull($dto->getFullName());
    }

    /**
     * Test get full name with only last name.
     */
    public function testGetFullNameWithOnlyLastName(): void
    {
        $dto = new UserDTO('test@example.com', null, 'Doe');

        $this->assertNull($dto->getFullName());
    }

    /**
     * Test get full name with no names.
     */
    public function testGetFullNameWithNoNames(): void
    {
        $dto = new UserDTO('test@example.com', null, null);

        $this->assertNull($dto->getFullName());
    }
}
