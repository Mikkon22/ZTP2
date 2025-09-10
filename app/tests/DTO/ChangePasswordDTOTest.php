<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\DTO;

use App\DTO\ChangePasswordDTO;
use PHPUnit\Framework\TestCase;

/**
 * Test class for ChangePasswordDTO.
 */
class ChangePasswordDTOTest extends TestCase
{
    /**
     * Test constructor with default values.
     */
    public function testConstructorWithDefaultValues(): void
    {
        $dto = new ChangePasswordDTO();

        $this->assertNull($dto->currentPassword);
        $this->assertNull($dto->newPassword);
        $this->assertNull($dto->confirmPassword);
    }

    /**
     * Test constructor with values.
     */
    public function testConstructorWithValues(): void
    {
        $dto = new ChangePasswordDTO('old123', 'new123', 'new123');

        $this->assertEquals('old123', $dto->currentPassword);
        $this->assertEquals('new123', $dto->newPassword);
        $this->assertEquals('new123', $dto->confirmPassword);
    }

    /**
     * Test setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        $dto = new ChangePasswordDTO();

        $dto->currentPassword = 'current123';
        $dto->newPassword = 'new123';
        $dto->confirmPassword = 'confirm123';

        $this->assertEquals('current123', $dto->currentPassword);
        $this->assertEquals('new123', $dto->newPassword);
        $this->assertEquals('confirm123', $dto->confirmPassword);
    }
}
