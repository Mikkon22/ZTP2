<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

/**
 * Base WebTestCase for controller tests.
 */
abstract class AbstractWebTestCase extends BaseWebTestCase
{
    /**
     * Set up test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Database will be set up when needed
    }

    /**
     * Create a client and set up the test database.
     *
     * @return \Symfony\Bundle\FrameworkBundle\KernelBrowser
     */
    protected function createClientAndSetUpDatabase()
    {
        $client = static::createClient();
        static::setUpTestDatabase();

        return $client;
    }

    /**
     * Set up test database with schema and fixtures.
     */
    protected static function setUpTestDatabase(): void
    {
        $container = static::getContainer();
        $em = $container->get(EntityManagerInterface::class);

        // Create schema
        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $schemaTool->dropSchema($metadatas);
        $schemaTool->createSchema($metadatas);

        // Load fixtures
        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($em);
        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($em, $purger);
        $passwordHasher = $container->get(\Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface::class);
        $executor->execute([
            new \App\DataFixtures\UserFixtures($passwordHasher),
            new \App\DataFixtures\CategoryFixtures(),
            new \App\DataFixtures\AppFixtures(),
        ]);
    }
}
