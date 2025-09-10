<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Command;

use App\Command\CreateAdminUserCommand;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Test class for CreateAdminUserCommand.
 */
class CreateAdminUserCommandTest extends TestCase
{
    private CreateAdminUserCommand $command;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Set up test.
     */
    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $this->command = new CreateAdminUserCommand(
            $this->entityManager,
            $this->passwordHasher
        );
    }

    /**
     * Test command name.
     */
    public function testCommandName(): void
    {
        $this->assertEquals('app:create-admin-user', $this->command->getName());
    }

    /**
     * Test command description.
     */
    public function testCommandDescription(): void
    {
        $this->assertStringContainsString('Creates the admin user', $this->command->getDescription());
    }
}
