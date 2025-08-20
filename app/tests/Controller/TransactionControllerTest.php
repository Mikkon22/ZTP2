<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\AbstractWebTestCase;

/**
 * Test class for TransactionController.
 */
class TransactionControllerTest extends AbstractWebTestCase
{
    /**
     * Test index page.
     */
    public function testIndex(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        $this->logIn($client);
        $client->request('GET', '/transaction/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    /**
     * Test new form.
     */
    public function testNewForm(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        $this->logIn($client);
        $client->request('GET', '/transaction/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    /**
     * Log in user for testing.
     *
     * @param \Symfony\Bundle\FrameworkBundle\KernelBrowser $client
     */
    private function logIn($client): void
    {
        $user = self::getContainer()->get(UserRepository::class)
            ->findOneBy([]);
        $client->loginUser($user);
    }
}
