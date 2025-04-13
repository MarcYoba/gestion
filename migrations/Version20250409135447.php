<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409135447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produit_a (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom VARCHAR(100) NOT NULL, prixvente DOUBLE PRECISION NOT NULL, prixachat DOUBLE PRECISION NOT NULL, quantite DOUBLE PRECISION NOT NULL, gain DOUBLE PRECISION NOT NULL, stockdebut DOUBLE PRECISION NOT NULL, cathegorie VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', type VARCHAR(100) NOT NULL, INDEX IDX_35363739A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_a ADD CONSTRAINT FK_35363739A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_a DROP FOREIGN KEY FK_35363739A76ED395');
        $this->addSql('DROP TABLE produit_a');
    }
}
