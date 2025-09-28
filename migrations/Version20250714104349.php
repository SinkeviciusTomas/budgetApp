<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250714104349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
        CREATE TEMPORARY TABLE __temp__transaction AS
        SELECT id, main_type, category, amount, description, date
        FROM "transaction"
    ');

        $this->addSql('DROP TABLE "transaction"');

        $this->addSql('
        CREATE TABLE "transaction" (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            main_type VARCHAR(20) NOT NULL,
            category VARCHAR(50) NOT NULL,
            amount DOUBLE PRECISION NOT NULL,
            description VARCHAR(128) DEFAULT NULL,
            date DATETIME NOT NULL,
            source_table VARCHAR(20) DEFAULT NULL,
            source_id INTEGER DEFAULT NULL
        )
    ');

        $this->addSql('
        INSERT INTO "transaction" (id, main_type, category, amount, description, date)
        SELECT id, main_type, category, amount, description, date
        FROM __temp__transaction
    ');

        $this->addSql('DROP TABLE __temp__transaction');
    }


    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__transaction AS SELECT id, main_type, category, amount, description, date FROM "transaction"');
        $this->addSql('DROP TABLE "transaction"');
        $this->addSql('CREATE TABLE "transaction" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, main_type VARCHAR(20) NOT NULL, category VARCHAR(50) NOT NULL, amount DOUBLE PRECISION NOT NULL, description VARCHAR(128) DEFAULT NULL, date DATETIME NOT NULL)');
        $this->addSql('INSERT INTO "transaction" (id, main_type, category, amount, description, date) SELECT id, main_type, category, amount, description, date FROM __temp__transaction');
        $this->addSql('DROP TABLE __temp__transaction');
    }
}
