<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250528103821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add type field to category table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE category ADD type VARCHAR(255) NOT NULL DEFAULT "expense"');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE category DROP type');
    }
}
