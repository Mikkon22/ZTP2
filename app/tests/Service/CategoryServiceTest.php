<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Tests\Service;

use App\Entity\Category;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use App\Tests\AbstractBaseTestCase;

/**
 * Test class for CategoryService.
 */
class CategoryServiceTest extends AbstractBaseTestCase
{
    private CategoryService $categoryService;
    private CategoryRepository $categoryRepository;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $entityManager = static::getContainer()->get(\Doctrine\ORM\EntityManagerInterface::class);
        $this->categoryService = new CategoryService($entityManager);
    }

    /**
     * Test getting categories by user.
     */
    public function testGetCategoriesByUser(): void
    {
        $user = $this->getUser();
        $categories = $this->categoryService->getCategoriesByUser($user);

        $this->assertIsArray($categories);
        $this->assertNotEmpty($categories);
        $this->assertInstanceOf(Category::class, $categories[0]);
    }

    /**
     * Test getting categories by user and type.
     */
    public function testGetCategoriesByUserAndType(): void
    {
        $user = $this->getUser();
        $incomeCategories = $this->categoryService->getCategoriesByUserAndType($user, 'income');
        $expenseCategories = $this->categoryService->getCategoriesByUserAndType($user, 'expense');

        $this->assertIsArray($incomeCategories);
        $this->assertIsArray($expenseCategories);
    }

    /**
     * Get a test user.
     *
     * @return User for testing purposes
     */
    private function getUser(): User
    {
        return static::getContainer()->get(\App\Repository\UserRepository::class)
            ->findOneBy(['email' => 'user@example.com']);
    }
}
