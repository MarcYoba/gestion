<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403115849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quantiteproduit (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, vente_id INT NOT NULL, user_id INT NOT NULL, quantite_restant DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F63A62D2F347EFB (produit_id), INDEX IDX_F63A62D27DC7170A (vente_id), INDEX IDX_F63A62D2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quantiteproduit ADD CONSTRAINT FK_F63A62D2F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE quantiteproduit ADD CONSTRAINT FK_F63A62D27DC7170A FOREIGN KEY (vente_id) REFERENCES vente (id)');
        $this->addSql('ALTER TABLE quantiteproduit ADD CONSTRAINT FK_F63A62D2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quantiteproduit DROP FOREIGN KEY FK_F63A62D2F347EFB');
        $this->addSql('ALTER TABLE quantiteproduit DROP FOREIGN KEY FK_F63A62D27DC7170A');
        $this->addSql('ALTER TABLE quantiteproduit DROP FOREIGN KEY FK_F63A62D2A76ED395');
        $this->addSql('DROP TABLE quantiteproduit');
    }
}
