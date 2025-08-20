<?php

declare(strict_types=1);

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Tests\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use App\Tests\AbstractBaseTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Test class for CreateDefaultCategoriesCommand.
 */
class CreateDefaultCategoriesCommandTest extends AbstractBaseTestCase
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
        $command = $application->find('app:create-default-categories');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Default categories created successfully!', $output);
        $this->assertEquals(0, $commandTester->getStatusCode());
    }

    /**
     * Test command execution with no admin user.
     */
    public function testExecuteWithNoAdminUser(): void
    {
        // Remove admin user specifically
        $adminUser = $this->userRepository->findOneBy(['email' => 'admin@example.com']);
        if ($adminUser) {
            $this->entityManager->remove($adminUser);
            $this->entityManager->flush();
        }

        // Verify admin user is removed
        $adminUser = $this->userRepository->findOneBy(['email' => 'admin@example.com']);
        $this->assertNull($adminUser, 'Admin user should be removed before test');

        $application = new Application(static::getContainer()->get('kernel'));
        $command = $application->find('app:create-default-categories');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Admin user not found', $output);
        $this->assertEquals(1, $commandTester->getStatusCode());
    }
}
