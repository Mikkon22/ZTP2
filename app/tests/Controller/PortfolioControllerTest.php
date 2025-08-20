<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use App\Entity\User;
use App\Entity\Portfolio;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Test class for PortfolioController.
 */
class PortfolioControllerTest extends AbstractWebTestCase
{
    /**
     * Test index page.
     */
    public function testIndex(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        $this->logIn($client);
        $client->request('GET', '/portfolio/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    /**
     * Test new portfolio creation.
     */
    public function testNewAndDelete(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        $this->logIn($client);
        $crawler = $client->request('GET', '/portfolio/new');
        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Create Portfolio')->form([
            'portfolio[name]' => 'Test Portfolio',
            'portfolio[type]' => 'cash',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/portfolio/');
        $client->followRedirect();
        $this->assertSelectorTextContains('body', 'Test Portfolio');
    }

    /**
     * Log in user for testing.
     *
     * @param \Symfony\Bundle\FrameworkBundle\KernelBrowser $client
     */
    private function logIn($client)
    {
        $user = self::getContainer()->get(EntityManagerInterface::class)
            ->getRepository(User::class)
            ->findOneBy([]);
        $client->loginUser($user);
    }
}
