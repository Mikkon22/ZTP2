<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use App\Entity\User;
use App\Repository\UserRepository;

/**
 * Test class for AdminController.
 */
class AdminControllerTest extends AbstractWebTestCase
{
    /**
     * Test users list page.
     */
    public function testUsers(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        $this->logInAsAdmin($client);
        $client->request('GET', '/admin/users');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    /**
     * Test edit user page.
     */
    public function testEditUser(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        $this->logInAsAdmin($client);

        // Get a user to edit
        $user = $this->getUser();
        $client->request('GET', '/admin/users/'.$user->getId().'/edit', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'en']);
        $this->assertResponseIsSuccessful();

        // Submit edit form
        $crawler = $client->request('GET', '/admin/users/'.$user->getId().'/edit', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'en']);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $form = $crawler->filter('form')->form([
            'admin_edit_user[email]' => 'updated@example.com',
            'admin_edit_user[firstName]' => 'Updated',
            'admin_edit_user[lastName]' => 'User',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/admin/users');
    }

    /**
     * Test change password page.
     */
    public function testChangePassword(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        $this->logInAsAdmin($client);

        $user = $this->getUser();
        $client->request('GET', '/admin/users/'.$user->getId().'/change-password', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'en']);
        $this->assertResponseIsSuccessful();

        // Submit password change form
        $crawler = $client->request('GET', '/admin/users/'.$user->getId().'/change-password', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'en']);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $form = $crawler->filter('form')->form([
            'admin_change_password[newPassword][first]' => 'newpassword123',
            'admin_change_password[newPassword][second]' => 'newpassword123',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/admin/users');
    }

    /**
     * Log in as admin user for testing.
     *
     * @param \Symfony\Bundle\FrameworkBundle\KernelBrowser $client
     */
    private function logInAsAdmin($client): void
    {
        $user = self::getContainer()->get(UserRepository::class)
            ->findOneBy(['email' => 'admin@example.com']);
        $client->loginUser($user);
    }

    /**
     * Get a test user.
     *
     * @return User for testing purposes
     */
    private function getUser(): User
    {
        return self::getContainer()->get(UserRepository::class)
            ->findOneBy(['email' => 'user@example.com']);
    }
}
