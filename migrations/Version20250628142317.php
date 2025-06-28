<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250628142317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE expense (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, amount DOUBLE PRECISION NOT NULL, description VARCHAR(128) DEFAULT NULL, type VARCHAR(50) NOT NULL, date DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE income (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, amount DOUBLE PRECISION NOT NULL, description VARCHAR(128) DEFAULT NULL, type VARCHAR(50) NOT NULL, date DATETIME NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE expense');
        $this->addSql('DROP TABLE income');
    }
}
