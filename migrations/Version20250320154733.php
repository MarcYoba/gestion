<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250320154733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE salaire (id INT AUTO_INCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, created_ad DATE NOT NULL, status VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employer ADD salaire_id INT NOT NULL');
        $this->addSql('ALTER TABLE employer ADD CONSTRAINT FK_DE4CF0662678C781 FOREIGN KEY (salaire_id) REFERENCES salaire (id)');
        $this->addSql('CREATE INDEX IDX_DE4CF0662678C781 ON employer (salaire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employer DROP FOREIGN KEY FK_DE4CF0662678C781');
        $this->addSql('DROP TABLE salaire');
        $this->addSql('DROP INDEX IDX_DE4CF0662678C781 ON employer');
        $this->addSql('ALTER TABLE employer DROP salaire_id');
    }
}
