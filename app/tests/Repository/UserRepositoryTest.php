<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\AbstractBaseTestCase;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Test class for UserRepository.
 */
class UserRepositoryTest extends AbstractBaseTestCase
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->userRepository = $this->entityManager->getRepository(User::class);
    }

    /**
     * Test finding user by email.
     */
    public function testFindByEmail(): void
    {
        $user = $this->userRepository->findOneBy(['email' => 'admin@example.com']);
        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * Test finding user by non-existent email.
     */
    public function testFindByNonExistentEmail(): void
    {
        $user = $this->userRepository->findOneBy(['email' => 'nonexistent@example.com']);
        $this->assertNull($user);
    }
}
