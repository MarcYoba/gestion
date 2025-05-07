<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507101017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vente_a (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, user_id INT DEFAULT NULL, produit_id INT DEFAULT NULL, type VARCHAR(100) NOT NULL, quantite DOUBLE PRECISION NOT NULL, prix DOUBLE PRECISION NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', cash DOUBLE PRECISION NOT NULL, reduction DOUBLE PRECISION NOT NULL, banque DOUBLE PRECISION NOT NULL, statut VARCHAR(100) NOT NULL, credit DOUBLE PRECISION NOT NULL, INDEX IDX_C13843FF19EB6921 (client_id), INDEX IDX_C13843FFA76ED395 (user_id), INDEX IDX_C13843FFF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vente_a ADD CONSTRAINT FK_C13843FF19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE vente_a ADD CONSTRAINT FK_C13843FFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vente_a ADD CONSTRAINT FK_C13843FFF347EFB FOREIGN KEY (produit_id) REFERENCES produit_a (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vente_a DROP FOREIGN KEY FK_C13843FF19EB6921');
        $this->addSql('ALTER TABLE vente_a DROP FOREIGN KEY FK_C13843FFA76ED395');
        $this->addSql('ALTER TABLE vente_a DROP FOREIGN KEY FK_C13843FFF347EFB');
        $this->addSql('DROP TABLE vente_a');
    }
}
