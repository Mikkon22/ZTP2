<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

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
} 