<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507150324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture_a ADD agence_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture_a ADD CONSTRAINT FK_46524B3CD725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_46524B3CD725330D ON facture_a (agence_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture_a DROP FOREIGN KEY FK_46524B3CD725330D');
        $this->addSql('DROP INDEX IDX_46524B3CD725330D ON facture_a');
        $this->addSql('ALTER TABLE facture_a DROP agence_id');
    }
}
