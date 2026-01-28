<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260127193230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inventaire (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventaire_a (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, user_id INT DEFAULT NULL, quantite DOUBLE PRECISION NOT NULL, inventaire DOUBLE PRECISION NOT NULL, createt_at DATE NOT NULL, INDEX IDX_235FE207F347EFB (produit_id), INDEX IDX_235FE207A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inventaire_a ADD CONSTRAINT FK_235FE207F347EFB FOREIGN KEY (produit_id) REFERENCES produit_a (id)');
        $this->addSql('ALTER TABLE inventaire_a ADD CONSTRAINT FK_235FE207A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE credit_fourniseur DROP FOREIGN KEY FK_AD9F5770670C757F');
        $this->addSql('ALTER TABLE credit_fourniseur DROP FOREIGN KEY FK_AD9F5770A76ED395');
        $this->addSql('ALTER TABLE credit_fourniseur DROP FOREIGN KEY FK_AD9F5770D725330D');
        $this->addSql('ALTER TABLE employer_agence DROP FOREIGN KEY FK_3B8E660E41CD9E7A');
        $this->addSql('ALTER TABLE employer_agence DROP FOREIGN KEY FK_3B8E660ED725330D');
        $this->addSql('DROP TABLE credit_fourniseur');
        $this->addSql('DROP TABLE employer_agence');
        $this->addSql('DROP TABLE limite');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE credit_fourniseur (id INT AUTO_INCREMENT NOT NULL, fournisseur_id INT DEFAULT NULL, user_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, banque VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, delai_paiement VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, tva DOUBLE PRECISION NOT NULL, montant DOUBLE PRECISION NOT NULL, echeance VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, createt_at DATE NOT NULL, INDEX IDX_AD9F5770670C757F (fournisseur_id), INDEX IDX_AD9F5770A76ED395 (user_id), INDEX IDX_AD9F5770D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE employer_agence (employer_id INT NOT NULL, agence_id INT NOT NULL, INDEX IDX_3B8E660E41CD9E7A (employer_id), INDEX IDX_3B8E660ED725330D (agence_id), PRIMARY KEY(employer_id, agence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE limite (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE credit_fourniseur ADD CONSTRAINT FK_AD9F5770670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE credit_fourniseur ADD CONSTRAINT FK_AD9F5770A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE credit_fourniseur ADD CONSTRAINT FK_AD9F5770D725330D FOREIGN KEY (agence_id) REFERENCES agence (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE employer_agence ADD CONSTRAINT FK_3B8E660E41CD9E7A FOREIGN KEY (employer_id) REFERENCES employer (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employer_agence ADD CONSTRAINT FK_3B8E660ED725330D FOREIGN KEY (agence_id) REFERENCES agence (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inventaire_a DROP FOREIGN KEY FK_235FE207F347EFB');
        $this->addSql('ALTER TABLE inventaire_a DROP FOREIGN KEY FK_235FE207A76ED395');
        $this->addSql('DROP TABLE inventaire');
        $this->addSql('DROP TABLE inventaire_a');
    }
}
