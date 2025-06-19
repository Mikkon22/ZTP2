<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250610113734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE category DROP FOREIGN KEY FK_64C19C17E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE portfolio DROP FOREIGN KEY FK_A9ED10627E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag DROP FOREIGN KEY FK_389B7837E3C61F9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1B96B5643
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaction DROP FOREIGN KEY FK_723705D112469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaction_tag DROP FOREIGN KEY FK_F8CD024A2FC0CB0F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaction_tag DROP FOREIGN KEY FK_F8CD024ABAD26311
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE portfolio
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tag
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE transaction
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE transaction_tag
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `user`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
