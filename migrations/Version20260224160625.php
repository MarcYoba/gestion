<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260224160625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE credit_fourniseur DROP FOREIGN KEY FK_AD9F5770670C757F');
        $this->addSql('ALTER TABLE credit_fourniseur DROP FOREIGN KEY FK_AD9F5770A76ED395');
        $this->addSql('ALTER TABLE credit_fourniseur DROP FOREIGN KEY FK_AD9F5770D725330D');
        $this->addSql('ALTER TABLE employer_agence DROP FOREIGN KEY FK_3B8E660E41CD9E7A');
        $this->addSql('ALTER TABLE employer_agence DROP FOREIGN KEY FK_3B8E660ED725330D');
        $this->addSql('DROP TABLE credit_fourniseur');
        $this->addSql('DROP TABLE employer_agence');
        $this->addSql('DROP TABLE limite');
        $this->addSql('ALTER TABLE inventaire ADD produit_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, ADD agence_id INT DEFAULT NULL, ADD quantite DOUBLE PRECISION NOT NULL, ADD inventaire DOUBLE PRECISION NOT NULL, ADD createt_at DATE NOT NULL, ADD ecart DOUBLE PRECISION NOT NULL, ADD justificatif VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE inventaire ADD CONSTRAINT FK_338920E0F347EFB FOREIGN KEY (produit_id) REFERENCES produit_a (id)');
        $this->addSql('ALTER TABLE inventaire ADD CONSTRAINT FK_338920E0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE inventaire ADD CONSTRAINT FK_338920E0D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_338920E0F347EFB ON inventaire (produit_id)');
        $this->addSql('CREATE INDEX IDX_338920E0A76ED395 ON inventaire (user_id)');
        $this->addSql('CREATE INDEX IDX_338920E0D725330D ON inventaire (agence_id)');
        $this->addSql('ALTER TABLE transfert_a ADD employer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transfert_a ADD CONSTRAINT FK_9E1E845441CD9E7A FOREIGN KEY (employer_id) REFERENCES employer (id)');
        $this->addSql('CREATE INDEX IDX_9E1E845441CD9E7A ON transfert_a (employer_id)');
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
        $this->addSql('ALTER TABLE inventaire DROP FOREIGN KEY FK_338920E0F347EFB');
        $this->addSql('ALTER TABLE inventaire DROP FOREIGN KEY FK_338920E0A76ED395');
        $this->addSql('ALTER TABLE inventaire DROP FOREIGN KEY FK_338920E0D725330D');
        $this->addSql('DROP INDEX IDX_338920E0F347EFB ON inventaire');
        $this->addSql('DROP INDEX IDX_338920E0A76ED395 ON inventaire');
        $this->addSql('DROP INDEX IDX_338920E0D725330D ON inventaire');
        $this->addSql('ALTER TABLE inventaire DROP produit_id, DROP user_id, DROP agence_id, DROP quantite, DROP inventaire, DROP createt_at, DROP ecart, DROP justificatif');
        $this->addSql('ALTER TABLE transfert_a DROP FOREIGN KEY FK_9E1E845441CD9E7A');
        $this->addSql('DROP INDEX IDX_9E1E845441CD9E7A ON transfert_a');
        $this->addSql('ALTER TABLE transfert_a DROP employer_id');
    }
}
