<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\DTO;

use App\DTO\TagDTO;
use PHPUnit\Framework\TestCase;

/**
 * Test class for TagDTO.
 */
class TagDTOTest extends TestCase
{
    /**
     * Test constructor with default values.
     */
    public function testConstructorWithDefaultValues(): void
    {
        $dto = new TagDTO();

        $this->assertNull($dto->name);
        $this->assertEquals('#007bff', $dto->color);
    }

    /**
     * Test constructor with values.
     */
    public function testConstructorWithValues(): void
    {
        $dto = new TagDTO('Test Tag', '#FF0000');

        $this->assertEquals('Test Tag', $dto->name);
        $this->assertEquals('#FF0000', $dto->color);
    }

    /**
     * Test setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        $dto = new TagDTO();

        $dto->name = 'Updated Tag';
        $dto->color = '#00FF00';

        $this->assertEquals('Updated Tag', $dto->name);
        $this->assertEquals('#00FF00', $dto->color);
    }
}
