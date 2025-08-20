<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Abstract base test case for database tests.
 */
abstract class AbstractBaseTestCase extends KernelTestCase
{
    /**
     * Boot the kernel and set up the test database.
     *
     * @param array $options
     *
     * @return \Symfony\Component\HttpKernel\KernelInterface
     */
    protected static function bootKernel(array $options = []): \Symfony\Component\HttpKernel\KernelInterface
    {
        $kernel = parent::bootKernel($options);

        // Ensure the test database schema is created
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

        return $kernel;
    }
}
