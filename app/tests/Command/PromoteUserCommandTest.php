<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>

declare(strict_types=1);
 */

namespace App\Tests\Command;

use App\Command\PromoteUserCommand;
use App\Repository\UserRepository;
use App\Tests\AbstractBaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Test class for PromoteUserCommand.
 */
class PromoteUserCommandTest extends AbstractBaseTestCase
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->userRepository = static::getContainer()->get(UserRepository::class);
    }

    /**
     * Test successful command execution.
     */
    public function testExecuteSuccess(): void
    {
        $application = new Application(static::bootKernel());
        $command = $application->find('app:promote-user');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'email' => 'admin@example.com',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('User "admin@example.com" is already an admin', $output);
        $this->assertEquals(0, $commandTester->getStatusCode());
    }

    /**
     * Test command execution with non-existent user.
     */
    public function testExecuteWithNonExistentUser(): void
    {
        $application = new Application(static::bootKernel());
        $command = $application->find('app:promote-user');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'email' => 'nonexistent@example.com',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('User with email "nonexistent@example.com" not found', $output);
        $this->assertEquals(1, $commandTester->getStatusCode());
    }
}
