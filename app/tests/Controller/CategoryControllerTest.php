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
 * Test class for CategoryController.
 */
class CategoryControllerTest extends AbstractWebTestCase
{
    /**
     * Test index page.
     */
    public function testIndex(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        $this->logIn($client);
        $client->request('GET', '/category/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    /**
     * Test new category creation.
     */
    public function testNewAndDelete(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        $this->logIn($client);
        $crawler = $client->request('GET', '/category/new');
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Create Category')->form([
            'category[name]' => 'Test Category',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/category/');
        $client->followRedirect();
        $this->assertSelectorTextContains('body', 'Test Category');
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
