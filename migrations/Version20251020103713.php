<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251020103713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE balance (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, classe INT NOT NULL, compte INT NOT NULL, intitule VARCHAR(255) NOT NULL, solde_initial_debit DOUBLE PRECISION NOT NULL, clear VARCHAR(255) NOT NULL, solde_initial_credit DOUBLE PRECISION NOT NULL, mouvement_debit DOUBLE PRECISION NOT NULL, mouvement_credit DOUBLE PRECISION NOT NULL, solde_final_debit DOUBLE PRECISION NOT NULL, sold_final_credit DOUBLE PRECISION NOT NULL, solde_global DOUBLE PRECISION NOT NULL, INDEX IDX_ACF41FFEA76ED395 (user_id), INDEX IDX_ACF41FFED725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE balance ADD CONSTRAINT FK_ACF41FFEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE balance ADD CONSTRAINT FK_ACF41FFED725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE employer_agence DROP FOREIGN KEY FK_3B8E660E41CD9E7A');
        $this->addSql('ALTER TABLE employer_agence DROP FOREIGN KEY FK_3B8E660ED725330D');
        $this->addSql('DROP TABLE employer_agence');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employer_agence (employer_id INT NOT NULL, agence_id INT NOT NULL, INDEX IDX_3B8E660E41CD9E7A (employer_id), INDEX IDX_3B8E660ED725330D (agence_id), PRIMARY KEY(employer_id, agence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE employer_agence ADD CONSTRAINT FK_3B8E660E41CD9E7A FOREIGN KEY (employer_id) REFERENCES employer (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employer_agence ADD CONSTRAINT FK_3B8E660ED725330D FOREIGN KEY (agence_id) REFERENCES agence (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE balance DROP FOREIGN KEY FK_ACF41FFEA76ED395');
        $this->addSql('ALTER TABLE balance DROP FOREIGN KEY FK_ACF41FFED725330D');
        $this->addSql('DROP TABLE balance');
    }
}
