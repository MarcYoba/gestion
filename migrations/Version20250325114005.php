<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250325114005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE versement ADD clients_id INT NOT NULL');
        $this->addSql('ALTER TABLE versement ADD CONSTRAINT FK_716E9367AB014612 FOREIGN KEY (clients_id) REFERENCES clients (id)');
        $this->addSql('CREATE INDEX IDX_716E9367AB014612 ON versement (clients_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE versement DROP FOREIGN KEY FK_716E9367AB014612');
        $this->addSql('DROP INDEX IDX_716E9367AB014612 ON versement');
        $this->addSql('ALTER TABLE versement DROP clients_id');
    }
}
