<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250413125159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achat_a (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, forunisseur_id INT NOT NULL, user_id INT NOT NULL, prix DOUBLE PRECISION NOT NULL, quantite DOUBLE PRECISION NOT NULL, montant DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_407B99E8F347EFB (produit_id), INDEX IDX_407B99E83AAF8973 (forunisseur_id), INDEX IDX_407B99E8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achat_a ADD CONSTRAINT FK_407B99E8F347EFB FOREIGN KEY (produit_id) REFERENCES produit_a (id)');
        $this->addSql('ALTER TABLE achat_a ADD CONSTRAINT FK_407B99E83AAF8973 FOREIGN KEY (forunisseur_id) REFERENCES fournisseur_a (id)');
        $this->addSql('ALTER TABLE achat_a ADD CONSTRAINT FK_407B99E8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat_a DROP FOREIGN KEY FK_407B99E8F347EFB');
        $this->addSql('ALTER TABLE achat_a DROP FOREIGN KEY FK_407B99E83AAF8973');
        $this->addSql('ALTER TABLE achat_a DROP FOREIGN KEY FK_407B99E8A76ED395');
        $this->addSql('DROP TABLE achat_a');
    }
}
