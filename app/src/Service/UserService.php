<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Service for handling user-related business logic.
 */
class UserService
{
    /**
     * Constructor.
     *
     * @param UserRepository              $userRepository the user repository
     * @param EntityManagerInterface      $entityManager  the entity manager
     * @param UserPasswordHasherInterface $passwordHasher the password hasher
     */
    public function __construct(private UserRepository $userRepository, private EntityManagerInterface $entityManager, private UserPasswordHasherInterface $passwordHasher)
    {
    }

    /**
     * Create a new user.
     *
     * @param User $user the user to create
     */
    public function createUser(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Update user information.
     *
     * @param User $user the user to update
     */
    public function updateUser(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Change user password.
     *
     * @param User   $user        the user
     * @param string $newPassword the new password
     */
    public function changePassword(User $user, string $newPassword): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);
        $this->entityManager->flush();
    }

    /**
     * Get user statistics.
     *
     * @param User $user the user
     *
     * @return array the statistics
     */
    public function getUserStatistics(User $user): array
    {
        // TODO: Implement user statistics calculation
        return [
            'total_portfolios' => $user->getPortfolios()->count(),
            'total_categories' => $user->getCategories()->count(),
            'total_tags' => $user->getTags()->count(),
            'total_transactions' => 0, // TODO: Calculate from portfolios
        ];
    }

    /**
     * Get user by ID.
     *
     * @param int $id the user ID
     *
     * @return User|null the user or null
     */
    public function getUserById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    /**
     * Get all users (for admin).
     *
     * @return array the users
     */
    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }
}
