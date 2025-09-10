<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Command;

use App\Command\RecalculatePortfolioBalancesCommand;
use App\Repository\PortfolioRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * Test class for RecalculatePortfolioBalancesCommand.
 */
class RecalculatePortfolioBalancesCommandTest extends TestCase
{
    private RecalculatePortfolioBalancesCommand $command;
    private PortfolioRepository $portfolioRepository;
    private EntityManagerInterface $entityManager;

    /**
     * Set up test.
     */
    protected function setUp(): void
    {
        $this->portfolioRepository = $this->createMock(PortfolioRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->command = new RecalculatePortfolioBalancesCommand(
            $this->entityManager
        );
    }

    /**
     * Test command name.
     */
    public function testCommandName(): void
    {
        $this->assertEquals('app:recalculate-portfolio-balances', $this->command->getName());
    }

    /**
     * Test command description.
     */
    public function testCommandDescription(): void
    {
        $this->assertStringContainsString('Recalculates all portfolio balances', $this->command->getDescription());
    }
}
