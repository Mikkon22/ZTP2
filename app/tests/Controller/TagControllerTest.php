<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Tests\Controller;

use App\Entity\Tag;
use App\Entity\User;
use App\Tests\AbstractWebTestCase;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Test class for TagController.
 */
class TagControllerTest extends AbstractWebTestCase
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
     * Test index page.
     */
    public function testIndex(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@example.com']);
        $client->loginUser($this->user);

        $client->request('GET', '/tag/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    /**
     * Test new tag page.
     */
    public function testNew(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@example.com']);
        $client->loginUser($this->user);

        $client->request('GET', '/tag/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    /**
     * Test edit tag page.
     */
    public function testEdit(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@example.com']);
        $client->loginUser($this->user);

        // Create a test tag
        $tag = new Tag();
        $tag->setName('Test Tag');
        $tag->setOwner($this->user);

        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        $client->request('GET', '/tag/'.$tag->getId().'/edit');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    /**
     * Test delete tag.
     */
    public function testDelete(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@example.com']);
        $client->loginUser($this->user);

        // Create a test tag
        $tag = new Tag();
        $tag->setName('Test Tag');
        $tag->setOwner($this->user);

        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        $client->request('POST', '/tag/'.$tag->getId());
        $this->assertResponseRedirects('/tag/');
    }
}
