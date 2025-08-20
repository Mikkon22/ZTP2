<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\Category;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Tests\AbstractBaseTestCase;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Test class for CategoryRepository.
 */
class CategoryRepositoryTest extends AbstractBaseTestCase
{
    private CategoryRepository $categoryRepository;
    private EntityManagerInterface $entityManager;
    private User $user;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->categoryRepository = $this->entityManager->getRepository(Category::class);
        $this->user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@example.com']);
    }

    /**
     * Test finding categories by user.
     */
    public function testFindByUser(): void
    {
        $categories = $this->categoryRepository->findByUser($this->user);
        $this->assertIsArray($categories);
    }

    /**
     * Test finding categories by user and type.
     */
    public function testFindByUserAndType(): void
    {
        $categories = $this->categoryRepository->findByUserAndType($this->user, 'expense');
        $this->assertIsArray($categories);
    }
}
