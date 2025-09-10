<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\DTO;

use App\DTO\PortfolioDTO;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PortfolioDTO.
 */
class PortfolioDTOTest extends TestCase
{
    /**
     * Test constructor with default values.
     */
    public function testConstructorWithDefaultValues(): void
    {
        $dto = new PortfolioDTO();

        $this->assertNull($dto->name);
        $this->assertNull($dto->description);
        $this->assertEquals(0.0, $dto->initialBalance);
        $this->assertEquals('PLN', $dto->currency);
    }

    /**
     * Test constructor with values.
     */
    public function testConstructorWithValues(): void
    {
        $dto = new PortfolioDTO('Test Portfolio', 'Test Description', 1000.50, 'USD');

        $this->assertEquals('Test Portfolio', $dto->name);
        $this->assertEquals('Test Description', $dto->description);
        $this->assertEquals(1000.50, $dto->initialBalance);
        $this->assertEquals('USD', $dto->currency);
    }

    /**
     * Test setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        $dto = new PortfolioDTO();

        $dto->name = 'Updated Portfolio';
        $dto->description = 'Updated Description';
        $dto->initialBalance = 2000.75;
        $dto->currency = 'EUR';

        $this->assertEquals('Updated Portfolio', $dto->name);
        $this->assertEquals('Updated Description', $dto->description);
        $this->assertEquals(2000.75, $dto->initialBalance);
        $this->assertEquals('EUR', $dto->currency);
    }
}
