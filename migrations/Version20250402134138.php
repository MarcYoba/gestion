<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402134138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE credit ADD vente_id INT NOT NULL');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFE7DC7170A FOREIGN KEY (vente_id) REFERENCES vente (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1CC16EFE7DC7170A ON credit (vente_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE credit DROP FOREIGN KEY FK_1CC16EFE7DC7170A');
        $this->addSql('DROP INDEX UNIQ_1CC16EFE7DC7170A ON credit');
        $this->addSql('ALTER TABLE credit DROP vente_id');
    }
}
