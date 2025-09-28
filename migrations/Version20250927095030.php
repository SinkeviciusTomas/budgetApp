<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250927095030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__expense AS SELECT id, amount, description, category, date, main_type FROM expense');
        $this->addSql('DROP TABLE expense');
        $this->addSql('CREATE TABLE expense (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, amount DOUBLE PRECISION NOT NULL, description VARCHAR(128) DEFAULT NULL, category VARCHAR(50) NOT NULL, date DATETIME NOT NULL, main_type VARCHAR(20) NOT NULL)');
        $this->addSql('INSERT INTO expense (id, amount, description, category, date, main_type) SELECT id, amount, description, category, date, main_type FROM __temp__expense');
        $this->addSql('DROP TABLE __temp__expense');
        $this->addSql('CREATE TEMPORARY TABLE __temp__income AS SELECT id, amount, description, category, date, main_type FROM income');
        $this->addSql('DROP TABLE income');
        $this->addSql('CREATE TABLE income (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, amount DOUBLE PRECISION NOT NULL, description VARCHAR(128) DEFAULT NULL, category VARCHAR(50) NOT NULL, date DATETIME NOT NULL, main_type VARCHAR(20) NOT NULL)');
        $this->addSql('INSERT INTO income (id, amount, description, category, date, main_type) SELECT id, amount, description, category, date, main_type FROM __temp__income');
        $this->addSql('DROP TABLE __temp__income');
        $this->addSql('CREATE TEMPORARY TABLE __temp__transaction AS SELECT id, main_type, category, amount, description, date, source_table, source_id FROM "transaction"');
        $this->addSql('DROP TABLE "transaction"');
        $this->addSql('CREATE TABLE "transaction" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, main_type VARCHAR(20) NOT NULL, category VARCHAR(50) NOT NULL, amount DOUBLE PRECISION NOT NULL, description VARCHAR(128) DEFAULT NULL, date DATETIME NOT NULL, source_table VARCHAR(20) NOT NULL, source_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO "transaction" (id, main_type, category, amount, description, date, source_table, source_id) SELECT id, main_type, category, amount, description, date, source_table, source_id FROM __temp__transaction');
        $this->addSql('DROP TABLE __temp__transaction');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__expense AS SELECT id, amount, description, category, date, main_type FROM expense');
        $this->addSql('DROP TABLE expense');
        $this->addSql('CREATE TABLE expense (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, amount DOUBLE PRECISION NOT NULL, description VARCHAR(128) DEFAULT NULL, category VARCHAR(50) NOT NULL, date DATETIME NOT NULL, main_type VARCHAR(20) DEFAULT NULL)');
        $this->addSql('INSERT INTO expense (id, amount, description, category, date, main_type) SELECT id, amount, description, category, date, main_type FROM __temp__expense');
        $this->addSql('DROP TABLE __temp__expense');
        $this->addSql('CREATE TEMPORARY TABLE __temp__income AS SELECT id, amount, description, category, date, main_type FROM income');
        $this->addSql('DROP TABLE income');
        $this->addSql('CREATE TABLE income (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, amount DOUBLE PRECISION NOT NULL, description VARCHAR(128) DEFAULT NULL, category VARCHAR(50) NOT NULL, date DATETIME NOT NULL, main_type VARCHAR(20) DEFAULT NULL)');
        $this->addSql('INSERT INTO income (id, amount, description, category, date, main_type) SELECT id, amount, description, category, date, main_type FROM __temp__income');
        $this->addSql('DROP TABLE __temp__income');
        $this->addSql('CREATE TEMPORARY TABLE __temp__transaction AS SELECT id, main_type, category, amount, description, date, source_table, source_id FROM "transaction"');
        $this->addSql('DROP TABLE "transaction"');
        $this->addSql('CREATE TABLE "transaction" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, main_type VARCHAR(20) NOT NULL, category VARCHAR(50) NOT NULL, amount DOUBLE PRECISION NOT NULL, description VARCHAR(128) DEFAULT NULL, date DATETIME NOT NULL, source_table VARCHAR(20) DEFAULT NULL, source_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO "transaction" (id, main_type, category, amount, description, date, source_table, source_id) SELECT id, main_type, category, amount, description, date, source_table, source_id FROM __temp__transaction');
        $this->addSql('DROP TABLE __temp__transaction');
    }
}
