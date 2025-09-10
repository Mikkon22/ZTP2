<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\DTO;

use App\DTO\TransactionDTO;
use App\Entity\Category;
use App\Entity\Portfolio;
use App\Entity\Tag;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Test class for TransactionDTO.
 */
class TransactionDTOTest extends TestCase
{
    /**
     * Test constructor with default values.
     */
    public function testConstructorWithDefaultValues(): void
    {
        $dto = new TransactionDTO();

        $this->assertNull($dto->title);
        $this->assertNull($dto->amount);
        $this->assertInstanceOf(\DateTime::class, $dto->date);
        $this->assertNull($dto->description);
        $this->assertNull($dto->transactionType);
        $this->assertNull($dto->portfolio);
        $this->assertNull($dto->category);
        $this->assertIsArray($dto->tags);
        $this->assertEmpty($dto->tags);
    }

    /**
     * Test setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        $dto = new TransactionDTO();

        $user = new User();
        $user->setEmail('test@example.com');

        $portfolio = new Portfolio();
        $portfolio->setName('Test Portfolio');
        $portfolio->setOwner($user);

        $category = new Category();
        $category->setName('Test Category');
        $category->setOwner($user);

        $tag = new Tag();
        $tag->setName('Test Tag');
        $tag->setOwner($user);

        $dto->title = 'Test Transaction';
        $dto->amount = 100.50;
        $dto->date = new \DateTime('2023-01-01');
        $dto->description = 'Test Description';
        $dto->transactionType = 'income';
        $dto->portfolio = $portfolio;
        $dto->category = $category;
        $dto->tags = [$tag];

        $this->assertEquals('Test Transaction', $dto->title);
        $this->assertEquals(100.50, $dto->amount);
        $this->assertEquals(new \DateTime('2023-01-01'), $dto->date);
        $this->assertEquals('Test Description', $dto->description);
        $this->assertEquals('income', $dto->transactionType);
        $this->assertSame($portfolio, $dto->portfolio);
        $this->assertSame($category, $dto->category);
        $this->assertContains($tag, $dto->tags);
    }

    /**
     * Test get signed amount for income.
     */
    public function testGetSignedAmountForIncome(): void
    {
        $dto = new TransactionDTO();
        $dto->amount = 100.50;
        $dto->transactionType = 'income';

        $this->assertEquals(100.50, $dto->getSignedAmount());
    }

    /**
     * Test get signed amount for expense.
     */
    public function testGetSignedAmountForExpense(): void
    {
        $dto = new TransactionDTO();
        $dto->amount = 100.50;
        $dto->transactionType = 'expense';

        $this->assertEquals(-100.50, $dto->getSignedAmount());
    }

    /**
     * Test set signed amount for income.
     */
    public function testSetSignedAmountForIncome(): void
    {
        $dto = new TransactionDTO();
        $dto->transactionType = 'income';
        $dto->setSignedAmount(100.50);

        $this->assertEquals(100.50, $dto->amount);
    }

    /**
     * Test set signed amount for expense.
     */
    public function testSetSignedAmountForExpense(): void
    {
        $dto = new TransactionDTO();
        $dto->transactionType = 'expense';
        $dto->setSignedAmount(-100.50);

        $this->assertEquals(100.50, $dto->amount);
    }
}
