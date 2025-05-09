<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507101723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE facture_a (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, client_id INT DEFAULT NULL, user_id INT DEFAULT NULL, vente_id INT DEFAULT NULL, quantite DOUBLE PRECISION NOT NULL, prix DOUBLE PRECISION NOT NULL, type VARCHAR(100) NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_46524B3CF347EFB (produit_id), INDEX IDX_46524B3C19EB6921 (client_id), INDEX IDX_46524B3CA76ED395 (user_id), INDEX IDX_46524B3C7DC7170A (vente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facture_a ADD CONSTRAINT FK_46524B3CF347EFB FOREIGN KEY (produit_id) REFERENCES produit_a (id)');
        $this->addSql('ALTER TABLE facture_a ADD CONSTRAINT FK_46524B3C19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE facture_a ADD CONSTRAINT FK_46524B3CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE facture_a ADD CONSTRAINT FK_46524B3C7DC7170A FOREIGN KEY (vente_id) REFERENCES vente_a (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture_a DROP FOREIGN KEY FK_46524B3CF347EFB');
        $this->addSql('ALTER TABLE facture_a DROP FOREIGN KEY FK_46524B3C19EB6921');
        $this->addSql('ALTER TABLE facture_a DROP FOREIGN KEY FK_46524B3CA76ED395');
        $this->addSql('ALTER TABLE facture_a DROP FOREIGN KEY FK_46524B3C7DC7170A');
        $this->addSql('DROP TABLE facture_a');
    }
}
