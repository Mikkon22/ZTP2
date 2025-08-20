<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\AbstractWebTestCase;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Test class for DefaultController.
 */
class DefaultControllerTest extends AbstractWebTestCase
{
    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Entity manager will be set up when needed
    }

    /**
     * Test index page for unauthenticated user.
     */
    public function testIndexUnauthenticated(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        $client->request('GET', '/');

        // Should redirect to registration page
        $this->assertResponseRedirects('/register');
    }

    /**
     * Test index page for authenticated user.
     */
    public function testIndexAuthenticated(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        // Create and login a user
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@example.com']);
        $client->loginUser($user);
        $client->request('GET', '/');

        // Should redirect to portfolio index
        $this->assertResponseRedirects('/portfolio/');
    }
}
