<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;

/**
 * Test class for RegistrationController.
 */
class RegistrationControllerTest extends AbstractWebTestCase
{
    /**
     * Test registration page.
     */
    public function testRegister(): void
    {
        $client = static::createClient();
        $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        // Test form submission
        $crawler = $client->request('GET', '/register', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'en']);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $form = $crawler->filter('form')->form([
            'registration_form[email]' => 'newuser@example.com',
            'registration_form[firstName]' => 'New',
            'registration_form[lastName]' => 'User',
            'registration_form[plainPassword]' => 'password123',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects();
    }
}
