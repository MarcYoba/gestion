<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402101249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, client_id INT NOT NULL, user_id INT NOT NULL, vente_id INT NOT NULL, quantite DOUBLE PRECISION NOT NULL, prix DOUBLE PRECISION NOT NULL, typepaiement VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FE866410F347EFB (produit_id), UNIQUE INDEX UNIQ_FE86641019EB6921 (client_id), INDEX IDX_FE866410A76ED395 (user_id), UNIQUE INDEX UNIQ_FE8664107DC7170A (vente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641019EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664107DC7170A FOREIGN KEY (vente_id) REFERENCES vente (id)');
        $this->addSql('ALTER TABLE vente ADD credit_id INT NOT NULL');
        $this->addSql('ALTER TABLE vente ADD CONSTRAINT FK_888A2A4CCE062FF9 FOREIGN KEY (credit_id) REFERENCES credit (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_888A2A4CCE062FF9 ON vente (credit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410F347EFB');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641019EB6921');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410A76ED395');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664107DC7170A');
        $this->addSql('DROP TABLE facture');
        $this->addSql('ALTER TABLE vente DROP FOREIGN KEY FK_888A2A4CCE062FF9');
        $this->addSql('DROP INDEX UNIQ_888A2A4CCE062FF9 ON vente');
        $this->addSql('ALTER TABLE vente DROP credit_id');
    }
}
