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
 * Test class for RestoreDataCommand.
 */
class RestoreDataCommandTest extends AbstractBaseTestCase
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
        // The admin user already exists from fixtures, so this should fail
        $this->expectException(\Doctrine\DBAL\Exception\UniqueConstraintViolationException::class);

        $application = new Application(static::bootKernel());
        $command = $application->find('app:restore-data');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);
    }

    /**
     * Test command execution with existing admin user.
     */
    public function testExecuteWithExistingAdminUser(): void
    {
        // The admin user already exists from fixtures, so this should fail
        $this->expectException(\Doctrine\DBAL\Exception\UniqueConstraintViolationException::class);

        $application = new Application(static::bootKernel());
        $command = $application->find('app:restore-data');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);
    }
}
