<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250505145923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_a ADD agence_id INT NOT NULL');
        $this->addSql('ALTER TABLE produit_a ADD CONSTRAINT FK_35363739D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_35363739D725330D ON produit_a (agence_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_a DROP FOREIGN KEY FK_35363739D725330D');
        $this->addSql('DROP INDEX IDX_35363739D725330D ON produit_a');
        $this->addSql('ALTER TABLE produit_a DROP agence_id');
    }
}
