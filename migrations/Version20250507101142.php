<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507101142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vente_a ADD agence_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vente_a ADD CONSTRAINT FK_C13843FFD725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_C13843FFD725330D ON vente_a (agence_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vente_a DROP FOREIGN KEY FK_C13843FFD725330D');
        $this->addSql('DROP INDEX IDX_C13843FFD725330D ON vente_a');
        $this->addSql('ALTER TABLE vente_a DROP agence_id');
    }
}
