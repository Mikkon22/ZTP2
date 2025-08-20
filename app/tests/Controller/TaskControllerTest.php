<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Tests\AbstractWebTestCase;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Test class for TaskController.
 */
class TaskControllerTest extends AbstractWebTestCase
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

        $client->request('GET', '/task/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
    }

    /**
     * Test new task page.
     */
    public function testNew(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@example.com']);
        $client->loginUser($this->user);

        $client->request('GET', '/task/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
        $this->assertSelectorTextContains('body', 'Example Task');
    }

    /**
     * Test show task page.
     */
    public function testShow(): void
    {
        $client = $this->createClientAndSetUpDatabase();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@example.com']);
        $client->loginUser($this->user);

        // Create a test task
        $task = new Task();
        $task->setTitle('Test Task');
        $task->setDescription('Test Description');
        $task->setUser($this->user);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $client->request('GET', '/task/'.$task->getId());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
        $this->assertSelectorTextContains('body', 'Test Task');
    }
}
