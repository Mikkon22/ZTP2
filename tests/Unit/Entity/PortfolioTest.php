<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Portfolio;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PortfolioTest extends TestCase
{
    private Portfolio $portfolio;
    private User $user;

    protected function setUp(): void
    {
        $this->portfolio = new Portfolio();
        $this->user = new User();
    }

    public function testPortfolioCreation(): void
    {
        $this->assertInstanceOf(Portfolio::class, $this->portfolio);
        $this->assertEmpty($this->portfolio->getName());
        $this->assertEmpty($this->portfolio->getType());
        $this->assertEquals(0.0, $this->portfolio->getBalance());
    }

    public function testPortfolioSettersAndGetters(): void
    {
        $name = 'Test Portfolio';
        $type = 'Investment';
        $balance = 1000.50;

        $this->portfolio->setName($name);
        $this->portfolio->setType($type);
        $this->portfolio->setBalance($balance);
        $this->portfolio->setOwner($this->user);

        $this->assertEquals($name, $this->portfolio->getName());
        $this->assertEquals($type, $this->portfolio->getType());
        $this->assertEquals($balance, $this->portfolio->getBalance());
        $this->assertEquals($this->user, $this->portfolio->getOwner());
    }

    public function testPortfolioBalance(): void
    {
        $this->portfolio->setBalance(1000.50);
        $this->assertEquals(1000.50, $this->portfolio->getBalance());

        $this->portfolio->setBalance(-500.25);
        $this->assertEquals(-500.25, $this->portfolio->getBalance());
    }

    public function testPortfolioOwner(): void
    {
        $this->portfolio->setOwner($this->user);
        $this->assertSame($this->user, $this->portfolio->getOwner());
    }
} 