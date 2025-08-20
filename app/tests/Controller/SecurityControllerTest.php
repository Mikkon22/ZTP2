<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

/**
 * Test class for SecurityController.
 */
class SecurityControllerTest extends AbstractWebTestCase
{
    /**
     * Test logout functionality.
     */
    public function testLogout(): void
    {
        $client = static::createClient();
        $client->request('GET', '/logout');
        $this->assertResponseRedirects();
    }

    /**
     * Test login page.
     */
    public function testLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }
}
