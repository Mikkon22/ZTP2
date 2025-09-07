<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

/**
 * Test class for HelloController.
 */
class HelloControllerTest extends AbstractWebTestCase
{
    /**
     * Test homepage redirect.
     */
    public function testHomepage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseRedirects('/register');
    }

    /**
     * Test index page.
     */
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/hello', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'en']);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
        $this->assertSelectorTextContains('body', 'John Doe');
    }

    /**
     * Test advanced page.
     */
    public function testAdvanced(): void
    {
        $client = static::createClient();
        $client->request('GET', '/hello/advanced');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }
}
