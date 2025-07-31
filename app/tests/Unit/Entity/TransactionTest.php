<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Transaction;
use App\Entity\Portfolio;
use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    private Transaction $transaction;
    private Portfolio $portfolio;
    private Category $category;

    protected function setUp(): void
    {
        $this->transaction = new Transaction();
        $this->portfolio = new Portfolio();
        $this->category = new Category();
    }

    public function testTransactionCreation(): void
    {
        $this->assertInstanceOf(Transaction::class, $this->transaction);
        $this->assertEmpty($this->transaction->getTitle());
        $this->assertEmpty($this->transaction->getDescription());
        $this->assertEquals(0.0, $this->transaction->getAmount());
    }

    public function testTransactionSettersAndGetters(): void
    {
        $title = 'Test Transaction';
        $description = 'Test Description';
        $amount = 100.50;
        $date = new \DateTime();

        $this->transaction->setTitle($title);
        $this->transaction->setDescription($description);
        $this->transaction->setAmount($amount);
        $this->transaction->setDate($date);
        $this->transaction->setPortfolio($this->portfolio);
        $this->transaction->setCategory($this->category);

        $this->assertEquals($title, $this->transaction->getTitle());
        $this->assertEquals($description, $this->transaction->getDescription());
        $this->assertEquals($amount, $this->transaction->getAmount());
        $this->assertEquals($date, $this->transaction->getDate());
        $this->assertEquals($this->portfolio, $this->transaction->getPortfolio());
        $this->assertEquals($this->category, $this->transaction->getCategory());
    }

    public function testTransactionAmount(): void
    {
        $this->transaction->setAmount(100.50);
        $this->assertEquals(100.50, $this->transaction->getAmount());

        $this->transaction->setAmount(-50.25);
        $this->assertEquals(-50.25, $this->transaction->getAmount());
    }

    public function testTransactionRelationships(): void
    {
        $this->transaction->setPortfolio($this->portfolio);
        $this->transaction->setCategory($this->category);

        $this->assertSame($this->portfolio, $this->transaction->getPortfolio());
        $this->assertSame($this->category, $this->transaction->getCategory());
    }
}
