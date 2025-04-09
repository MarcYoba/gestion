<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409111929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE versement_a (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, user_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, om DOUBLE PRECISION NOT NULL, banque DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description VARCHAR(255) NOT NULL, INDEX IDX_5F54C5A19EB6921 (client_id), INDEX IDX_5F54C5AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE versement_a ADD CONSTRAINT FK_5F54C5A19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE versement_a ADD CONSTRAINT FK_5F54C5AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE versement_a DROP FOREIGN KEY FK_5F54C5A19EB6921');
        $this->addSql('ALTER TABLE versement_a DROP FOREIGN KEY FK_5F54C5AA76ED395');
        $this->addSql('DROP TABLE versement_a');
    }
}
