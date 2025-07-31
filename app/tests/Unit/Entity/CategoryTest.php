<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Category;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    private Category $category;
    private User $user;

    protected function setUp(): void
    {
        $this->category = new Category();
        $this->user = new User();
    }

    public function testCategoryCreation(): void
    {
        $this->assertInstanceOf(Category::class, $this->category);
        $this->assertNull($this->category->getId());
        $this->assertNull($this->category->getName());
        $this->assertEquals('expense', $this->category->getType());
        $this->assertNull($this->category->getDescription());
        $this->assertNull($this->category->getColor());
        $this->assertNull($this->category->getOwner());
    }

    public function testSettersAndGetters(): void
    {
        $this->category->setName('Food');
        $this->category->setType('income');
        $this->category->setDescription('Groceries and restaurants');
        $this->category->setColor('#ff0000');
        $this->category->setOwner($this->user);

        $this->assertEquals('Food', $this->category->getName());
        $this->assertEquals('income', $this->category->getType());
        $this->assertEquals('Groceries and restaurants', $this->category->getDescription());
        $this->assertEquals('#ff0000', $this->category->getColor());
        $this->assertSame($this->user, $this->category->getOwner());
    }

    public function testInvalidTypeThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->category->setType('invalid_type');
    }
}
