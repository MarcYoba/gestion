<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507143304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quantiteproduit_a (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, vente_id INT DEFAULT NULL, user_id INT DEFAULT NULL, quantite DOUBLE PRECISION NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_56FD5379F347EFB (produit_id), INDEX IDX_56FD53797DC7170A (vente_id), INDEX IDX_56FD5379A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quantiteproduit_a ADD CONSTRAINT FK_56FD5379F347EFB FOREIGN KEY (produit_id) REFERENCES produit_a (id)');
        $this->addSql('ALTER TABLE quantiteproduit_a ADD CONSTRAINT FK_56FD53797DC7170A FOREIGN KEY (vente_id) REFERENCES vente_a (id)');
        $this->addSql('ALTER TABLE quantiteproduit_a ADD CONSTRAINT FK_56FD5379A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quantiteproduit_a DROP FOREIGN KEY FK_56FD5379F347EFB');
        $this->addSql('ALTER TABLE quantiteproduit_a DROP FOREIGN KEY FK_56FD53797DC7170A');
        $this->addSql('ALTER TABLE quantiteproduit_a DROP FOREIGN KEY FK_56FD5379A76ED395');
        $this->addSql('DROP TABLE quantiteproduit_a');
    }
}
