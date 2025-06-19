<?php

/**
 * This file is part of the ZTP2-2 project.
 *
 * (c) Your Name <your@email.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Data fixture for loading default categories.
 */
class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Load categories into the database.
     */
    public function load(ObjectManager $manager): void
    {
        /** @var User $admin */
        $admin = $this->getReference('admin-user', User::class);
        /** @var User $user */
        $user = $this->getReference('default-user', User::class);

        // Income categories
        $incomeCategories = [
            ['name' => 'Salary', 'color' => '#28a745', 'description' => 'Regular income from employment'],
            ['name' => 'Side Projects', 'color' => '#17a2b8', 'description' => 'Income from freelance work and side projects'],
            ['name' => 'Gift', 'color' => '#dc3545', 'description' => 'Gifts received'],
            ['name' => 'Others', 'color' => '#6c757d', 'description' => 'Other sources of income'],
        ];

        // Expense categories
        $expenseCategories = [
            ['name' => 'Food & Dining', 'color' => '#fd7e14', 'description' => 'Groceries, restaurants, and food delivery'],
            ['name' => 'Transportation', 'color' => '#20c997', 'description' => 'Public transport, fuel, and vehicle maintenance'],
            ['name' => 'Housing', 'color' => '#6f42c1', 'description' => 'Rent, utilities, and home maintenance'],
            ['name' => 'Entertainment', 'color' => '#e83e8c', 'description' => 'Movies, games, and hobbies'],
            ['name' => 'Shopping', 'color' => '#ffc107', 'description' => 'Clothing, electronics, and personal items'],
            ['name' => 'Healthcare', 'color' => '#0dcaf0', 'description' => 'Medical expenses and health insurance'],
            ['name' => 'Education', 'color' => '#198754', 'description' => 'Courses, books, and training'],
            ['name' => 'Others', 'color' => '#6c757d', 'description' => 'Other expenses'],
        ];

        // Create categories for admin user
        foreach ($incomeCategories as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setType('income');
            $category->setColor($categoryData['color']);
            $category->setDescription($categoryData['description']);
            $category->setOwner($admin);
            $manager->persist($category);
        }

        foreach ($expenseCategories as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setType('expense');
            $category->setColor($categoryData['color']);
            $category->setDescription($categoryData['description']);
            $category->setOwner($admin);
            $manager->persist($category);
        }

        // Create categories for regular user
        foreach ($incomeCategories as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setType('income');
            $category->setColor($categoryData['color']);
            $category->setDescription($categoryData['description']);
            $category->setOwner($user);
            $manager->persist($category);
        }

        foreach ($expenseCategories as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setType('expense');
            $category->setColor($categoryData['color']);
            $category->setDescription($categoryData['description']);
            $category->setOwner($user);
            $manager->persist($category);
        }

        $manager->flush();
    }

    /**
     * Get the dependencies for this fixture.
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
