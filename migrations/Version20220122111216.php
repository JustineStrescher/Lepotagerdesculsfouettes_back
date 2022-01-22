<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220122111216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, summary VARCHAR(255) DEFAULT NULL, available SMALLINT NOT NULL, stock INT NOT NULL, description VARCHAR(255) DEFAULT NULL, unit_price DOUBLE PRECISION DEFAULT NULL, weight_price DOUBLE PRECISION DEFAULT NULL, picture VARCHAR(2083) DEFAULT NULL, weight DOUBLE PRECISION NOT NULL, updated_at DATETIME DEFAULT NULL, creation_at DATETIME NOT NULL, slug VARCHAR(255) NOT NULL, hihlighted SMALLINT DEFAULT NULL, online SMALLINT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE product');
    }
}
