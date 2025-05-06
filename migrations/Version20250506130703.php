<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506130703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat_a ADD agence_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE achat_a ADD CONSTRAINT FK_407B99E8D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_407B99E8D725330D ON achat_a (agence_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat_a DROP FOREIGN KEY FK_407B99E8D725330D');
        $this->addSql('DROP INDEX IDX_407B99E8D725330D ON achat_a');
        $this->addSql('ALTER TABLE achat_a DROP agence_id');
    }
}
