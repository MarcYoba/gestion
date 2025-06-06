<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506120528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fournisseur_a ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fournisseur_a ADD CONSTRAINT FK_D08E4BEBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D08E4BEBA76ED395 ON fournisseur_a (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fournisseur_a DROP FOREIGN KEY FK_D08E4BEBA76ED395');
        $this->addSql('DROP INDEX IDX_D08E4BEBA76ED395 ON fournisseur_a');
        $this->addSql('ALTER TABLE fournisseur_a DROP user_id');
    }
}
