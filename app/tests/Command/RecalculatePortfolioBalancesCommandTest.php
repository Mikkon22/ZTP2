<?php

declare(strict_types=1);

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Tests\Command;

use App\Tests\AbstractBaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Test class for RecalculatePortfolioBalancesCommand.
 */
class RecalculatePortfolioBalancesCommandTest extends AbstractBaseTestCase
{
    private EntityManagerInterface $entityManager;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
    }

    /**
     * Test successful command execution.
     */
    public function testExecuteSuccess(): void
    {
        $application = new Application(static::bootKernel());
        $command = $application->find('app:recalculate-portfolio-balances');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('All portfolio balances are correct', $output);
        $this->assertEquals(0, $commandTester->getStatusCode());
    }
}
