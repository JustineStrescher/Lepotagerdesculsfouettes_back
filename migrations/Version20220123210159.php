<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220123210159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_command ADD command_id INT NOT NULL, ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_command ADD CONSTRAINT FK_5F13F16433E1689A FOREIGN KEY (command_id) REFERENCES command (id)');
        $this->addSql('ALTER TABLE product_command ADD CONSTRAINT FK_5F13F1644584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_5F13F16433E1689A ON product_command (command_id)');
        $this->addSql('CREATE INDEX IDX_5F13F1644584665A ON product_command (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_command DROP FOREIGN KEY FK_5F13F16433E1689A');
        $this->addSql('ALTER TABLE product_command DROP FOREIGN KEY FK_5F13F1644584665A');
        $this->addSql('DROP INDEX IDX_5F13F16433E1689A ON product_command');
        $this->addSql('DROP INDEX IDX_5F13F1644584665A ON product_command');
        $this->addSql('ALTER TABLE product_command DROP command_id, DROP product_id');
    }
}
