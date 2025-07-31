<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class BaseTestCase extends KernelTestCase
{
    protected static function bootKernel(array $options = []): ContainerInterface
    {
        $container = parent::bootKernel($options);
        
        // Set up database schema for tests
        $entityManager = $container->get(EntityManagerInterface::class);
        $connection = $entityManager->getConnection();
        
        // Create schema if it doesn't exist
        $schemaManager = $connection->createSchemaManager();
        $schema = $schemaManager->introspectSchema();
        
        // Drop all tables and recreate them
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0');
        foreach ($schema->getTableNames() as $tableName) {
            $connection->executeStatement('DROP TABLE IF EXISTS ' . $tableName);
        }
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');
        
        // Create schema
        $queries = $schema->toSql($connection->getDatabasePlatform());
        foreach ($queries as $query) {
            $connection->executeStatement($query);
        }
        
        return $container;
    }
} 