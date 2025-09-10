<?php

/**
 * This file is part of the ZTP2-2 project.
 * (c) Your Name <your@email.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Mapper;

use App\DTO\TransactionDTO;
use App\Entity\Category;
use App\Entity\Portfolio;
use App\Entity\Tag;
use App\Entity\Transaction;
use App\Entity\User;
use App\Mapper\TransactionMapper;
use PHPUnit\Framework\TestCase;

/**
 * Test class for TransactionMapper.
 */
class TransactionMapperTest extends TestCase
{
    private TransactionMapper $mapper;

    /**
     * Set up test.
     */
    protected function setUp(): void
    {
        $this->mapper = new TransactionMapper();
    }

    /**
     * Test entity to DTO conversion.
     */
    public function testEntityToDto(): void
    {
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

        $transaction = new Transaction();
        $transaction->setTitle('Test Transaction');
        $transaction->setAmount(100.50);
        $transaction->setDate(new \DateTime('2023-01-01'));
        $transaction->setDescription('Test Description');
        $transaction->setPortfolio($portfolio);
        $transaction->setCategory($category);
        $transaction->addTag($tag);

        $dto = $this->mapper->entityToDto($transaction);

        $this->assertInstanceOf(TransactionDTO::class, $dto);
        $this->assertEquals('Test Transaction', $dto->title);
        $this->assertEquals(100.50, $dto->amount);
        $this->assertEquals(new \DateTime('2023-01-01'), $dto->date);
        $this->assertEquals('Test Description', $dto->description);
        $this->assertSame($portfolio, $dto->portfolio);
        $this->assertSame($category, $dto->category);
        $this->assertContains($tag, $dto->tags);
    }

    /**
     * Test DTO to entity conversion with new entity.
     */
    public function testDtoToEntityWithNewEntity(): void
    {
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

        $dto = new TransactionDTO();
        $dto->title = 'New Transaction';
        $dto->amount = 200.75;
        $dto->date = new \DateTime('2023-02-01');
        $dto->description = 'New Description';
        $dto->transactionType = 'expense';
        $dto->portfolio = $portfolio;
        $dto->category = $category;
        $dto->tags = [$tag];

        $transaction = $this->mapper->dtoToEntity($dto);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals('New Transaction', $transaction->getTitle());
        $this->assertEquals(-200.75, $transaction->getAmount());
        $this->assertEquals(new \DateTime('2023-02-01'), $transaction->getDate());
        $this->assertEquals('New Description', $transaction->getDescription());
        $this->assertSame($portfolio, $transaction->getPortfolio());
        $this->assertSame($category, $transaction->getCategory());
        $this->assertContains($tag, $transaction->getTags()->toArray());
    }

    /**
     * Test update entity from DTO.
     */
    public function testUpdateEntityFromDto(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $portfolio = new Portfolio();
        $portfolio->setName('Test Portfolio');
        $portfolio->setOwner($user);

        $category = new Category();
        $category->setName('Test Category');
        $category->setOwner($user);

        $transaction = new Transaction();
        $transaction->setTitle('Original Title');
        $transaction->setAmount(100.00);
        $transaction->setDate(new \DateTime('2023-01-01'));
        $transaction->setDescription('Original Description');
        $transaction->setPortfolio($portfolio);
        $transaction->setCategory($category);

        $dto = new TransactionDTO();
        $dto->title = 'Updated Title';
        $dto->amount = 250.50;
        $dto->date = new \DateTime('2023-04-01');
        $dto->description = 'Updated Description';
        $dto->transactionType = 'expense';
        $dto->portfolio = $portfolio;
        $dto->category = $category;

        $result = $this->mapper->updateEntityFromDto($transaction, $dto);

        $this->assertSame($transaction, $result);
        $this->assertEquals('Updated Title', $transaction->getTitle());
        $this->assertEquals(-250.50, $transaction->getAmount());
        $this->assertEquals(new \DateTime('2023-04-01'), $transaction->getDate());
        $this->assertEquals('Updated Description', $transaction->getDescription());
    }
}
