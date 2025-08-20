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
 * Test class for ProfileController.
 */
class ProfileControllerTest extends AbstractWebTestCase
{
    private EntityManagerInterface $entityManager;
    private User $user;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Entity manager and user will be set up when needed
    }

    /**
     * Test change password page.
     */
    public function testChangePassword(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@example.com']);
        $client->loginUser($this->user);
        $client->request('GET', '/profile/change-password');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }
}
