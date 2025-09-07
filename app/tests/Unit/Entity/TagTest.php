<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Tests\Unit\Entity;

use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Transaction;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Tag entity.
 */
class TagTest extends TestCase
{
    private Tag $tag;
    private User $user;
    private Transaction $transaction;

    /**
     * Set up test environment.
     */
    protected function setUp(): void
    {
        $this->tag = new Tag();
        $this->user = new User();
        $this->transaction = new Transaction();
    }

    /**
     * Test tag creation.
     */
    public function testTagCreation(): void
    {
        $this->assertInstanceOf(Tag::class, $this->tag);
        $this->assertNull($this->tag->getId());
        $this->assertNull($this->tag->getName());
        $this->assertNull($this->tag->getOwner());
        $this->assertCount(0, $this->tag->getTransactions());
    }

    /**
     * Test setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        $this->tag->setName('Urgent');
        $this->tag->setOwner($this->user);
        $this->assertEquals('Urgent', $this->tag->getName());
        $this->assertSame($this->user, $this->tag->getOwner());
    }

    /**
     * Test add and remove transaction.
     */
    public function testAddAndRemoveTransaction(): void
    {
        $this->assertCount(0, $this->tag->getTransactions());
        $this->tag->addTransaction($this->transaction);
        $this->assertCount(1, $this->tag->getTransactions());
        $this->assertTrue($this->tag->getTransactions()->contains($this->transaction));
        $this->tag->removeTransaction($this->transaction);
        $this->assertCount(0, $this->tag->getTransactions());
    }

    /**
     * Test to string method.
     */
    public function testToString(): void
    {
        $this->tag->setName('Important');
        $this->assertEquals('Important', (string) $this->tag);
    }
}
