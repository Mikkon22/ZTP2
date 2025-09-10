<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Test class for UserService.
 */
class UserServiceTest extends TestCase
{
    private UserService $service;
    private UserRepository $repository;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Set up test.
     */
    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->service = new UserService($this->repository, $this->entityManager, $this->passwordHasher);
    }

    /**
     * Test create user.
     */
    public function testCreateUser(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setFirstName('John');
        $user->setLastName('Doe');

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($user);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->service->createUser($user);
    }

    /**
     * Test update user.
     */
    public function testUpdateUser(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setFirstName('John');
        $user->setLastName('Doe');

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($user);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->service->updateUser($user);
    }

    /**
     * Test change password.
     */
    public function testChangePassword(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $this->passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->with($user, 'newpassword123')
            ->willReturn('hashed_password');

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->service->changePassword($user, 'newpassword123');

        $this->assertEquals('hashed_password', $user->getPassword());
    }
}
