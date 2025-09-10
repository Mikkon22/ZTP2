<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Mapper;

use App\DTO\PortfolioDTO;
use App\Entity\Portfolio;
use App\Entity\User;
use App\Mapper\PortfolioMapper;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PortfolioMapper.
 */
class PortfolioMapperTest extends TestCase
{
    private PortfolioMapper $mapper;

    /**
     * Set up test.
     */
    protected function setUp(): void
    {
        $this->mapper = new PortfolioMapper();
    }

    /**
     * Test entity to DTO conversion.
     */
    public function testEntityToDto(): void
    {
        $user = new User();
        $user->setEmail('john.doe@example.com');
        $user->setFirstName('John');
        $user->setLastName('Doe');

        $portfolio = new Portfolio();
        $portfolio->setName('Test Portfolio');
        $portfolio->setType('Test Type');
        $portfolio->setBalance(1000.50);
        $portfolio->setOwner($user);

        $dto = $this->mapper->entityToDto($portfolio);

        $this->assertInstanceOf(PortfolioDTO::class, $dto);
        $this->assertEquals('Test Portfolio', $dto->name);
        $this->assertEquals('Test Type', $dto->description);
        $this->assertEquals(1000.50, $dto->initialBalance);
    }

    /**
     * Test DTO to entity conversion with new entity.
     */
    public function testDtoToEntityWithNewEntity(): void
    {
        $dto = new PortfolioDTO();
        $dto->name = 'New Portfolio';
        $dto->description = 'New Type';
        $dto->initialBalance = 2000.75;

        $portfolio = $this->mapper->dtoToEntity($dto);

        $this->assertInstanceOf(Portfolio::class, $portfolio);
        $this->assertEquals('New Portfolio', $portfolio->getName());
        $this->assertEquals('New Type', $portfolio->getType());
        $this->assertEquals(2000.75, $portfolio->getBalance());
    }

    /**
     * Test update entity from DTO.
     */
    public function testUpdateEntityFromDto(): void
    {
        $user = new User();
        $user->setEmail('john.doe@example.com');
        $user->setFirstName('John');
        $user->setLastName('Doe');

        $portfolio = new Portfolio();
        $portfolio->setName('Old Portfolio');
        $portfolio->setType('Old Type');
        $portfolio->setBalance(500.00);
        $portfolio->setOwner($user);

        $dto = new PortfolioDTO();
        $dto->name = 'Updated Portfolio';
        $dto->description = 'Updated Type';
        $dto->initialBalance = 1500.25;

        $this->mapper->updateEntityFromDto($portfolio, $dto);

        $this->assertEquals('Updated Portfolio', $portfolio->getName());
        $this->assertEquals('Updated Type', $portfolio->getType());
        $this->assertEquals(1500.25, $portfolio->getBalance());
    }
}
