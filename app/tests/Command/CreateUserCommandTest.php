<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

declare(strict_types=1);

namespace App\Tests\Command;

use App\Command\CreateUserCommand;
use App\Repository\UserRepository;
use App\Tests\AbstractBaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Test class for CreateUserCommand.
 */
class CreateUserCommandTest extends AbstractBaseTestCase
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
        $command = $application->find('app:create-user');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'email' => 'test@example.com',
            'password' => 'password123',
            'firstName' => 'Test',
            'lastName' => 'User',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('User test@example.com was created successfully!', $output);
        $this->assertEquals(0, $commandTester->getStatusCode());

        // Verify user was created
        $user = $this->userRepository->findOneBy(['email' => 'test@example.com']);
        $this->assertNotNull($user);
        $this->assertTrue(in_array('ROLE_USER', $user->getRoles()));
    }

    /**
     * Test command execution with existing user.
     */
    public function testExecuteWithExistingUser(): void
    {
        // First create a user
        $application = new Application(static::bootKernel());
        $command = $application->find('app:create-user');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'email' => 'existing@example.com',
            'password' => 'password123',
            'firstName' => 'Existing',
            'lastName' => 'User',
        ]);

        // Verify first creation was successful
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('User existing@example.com was created successfully!', $output);
        $this->assertEquals(0, $commandTester->getStatusCode());

        // Now try to create the same user again - this should throw an exception
        $this->expectException(\Doctrine\DBAL\Exception\UniqueConstraintViolationException::class);

        $commandTester->execute([
            'email' => 'existing@example.com',
            'password' => 'password123',
            'firstName' => 'Existing',
            'lastName' => 'User',
        ]);
    }
}
