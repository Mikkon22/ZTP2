<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service for handling category-related business logic.
 */
class CategoryService
{
    /**
     * CategoryService constructor.
     *
     * @param EntityManagerInterface $entityManager the entity manager
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * Creates default categories for a user.
     *
     * @param User $user the user entity for whom to create categories
     */
    public function createDefaultCategories(User $user): void
    {
        // Default expense categories
        $expenseCategories = [
            ['name' => 'Food & Dining', 'color' => '#FF5733', 'type' => 'expense'],
            ['name' => 'Transportation', 'color' => '#33FF57', 'type' => 'expense'],
            ['name' => 'Housing', 'color' => '#3357FF', 'type' => 'expense'],
            ['name' => 'Entertainment', 'color' => '#FF33F5', 'type' => 'expense'],
            ['name' => 'Shopping', 'color' => '#33FFF5', 'type' => 'expense'],
            ['name' => 'Healthcare', 'color' => '#F5FF33', 'type' => 'expense'],
            ['name' => 'Utilities', 'color' => '#FF3333', 'type' => 'expense'],
        ];

        // Default income categories
        $incomeCategories = [
            ['name' => 'Salary', 'color' => '#33FF33', 'type' => 'income'],
            ['name' => 'Investments', 'color' => '#3333FF', 'type' => 'income'],
            ['name' => 'Gifts', 'color' => '#FF33FF', 'type' => 'income'],
            ['name' => 'Side Business', 'color' => '#FFFF33', 'type' => 'income'],
        ];

        $allCategories = array_merge($expenseCategories, $incomeCategories);

        foreach ($allCategories as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setColor($categoryData['color']);
            $category->setType($categoryData['type']);
            $category->setOwner($user);

            $this->entityManager->persist($category);
        }

        $this->entityManager->flush();
    }

    /**
     * Get categories by user.
     *
     * @param User $user the user
     *
     * @return Category[] the categories
     */
    public function getCategoriesByUser(User $user): array
    {
        return $this->entityManager->getRepository(Category::class)->findByUser($user);
    }

    /**
     * Get categories by user and type.
     *
     * @param User   $user the user
     * @param string $type the category type
     *
     * @return Category[] the categories
     */
    public function getCategoriesByUserAndType(User $user, string $type): array
    {
        return $this->entityManager->getRepository(Category::class)->findByUserAndType($user, $type);
    }
}
