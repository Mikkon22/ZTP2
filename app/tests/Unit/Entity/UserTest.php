<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * Test class for User entity.
 */
class UserTest extends TestCase
{
    /**
     * Set up test environment.
     */
    protected function setUp(): void
    {
        $this->user = new User();
    }

    /**
     * Test user creation.
     */
    public function testUserCreation(): void
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertEmpty($this->user->getEmail());
        $this->assertEmpty($this->user->getPassword());
        $this->assertEmpty($this->user->getFirstName());
        $this->assertEmpty($this->user->getLastName());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
    }

    /**
     * Test user setters and getters.
     */
    public function testUserSettersAndGetters(): void
    {
        $email = 'test@example.com';
        $password = 'password123';
        $firstName = 'John';
        $lastName = 'Doe';
        $roles = ['ROLE_USER'];

        $this->user->setEmail($email);
        $this->user->setPassword($password);
        $this->user->setFirstName($firstName);
        $this->user->setLastName($lastName);
        $this->user->setRoles($roles);

        $this->assertEquals($email, $this->user->getEmail());
        $this->assertEquals($password, $this->user->getPassword());
        $this->assertEquals($firstName, $this->user->getFirstName());
        $this->assertEquals($lastName, $this->user->getLastName());
        $this->assertEquals($roles, $this->user->getRoles());
    }

    /**
     * Test user roles.
     */
    public function testUserRoles(): void
    {
        $this->user->setRoles(['ROLE_USER']);
        $roles = $this->user->getRoles();
        $this->assertContains('ROLE_USER', $roles);
        $this->assertCount(1, $roles);

        $this->user->setRoles(['ROLE_ADMIN']);
        $roles = $this->user->getRoles();
        $this->assertContains('ROLE_ADMIN', $roles);
        $this->assertContains('ROLE_USER', $roles);
        $this->assertCount(2, $roles);
    }

    /**
     * Test user full name.
     */
    public function testUserFullName(): void
    {
        $this->user->setFirstName('John');
        $this->user->setLastName('Doe');

        $this->assertEquals('John Doe', $this->user->getFullName());
    }
}
