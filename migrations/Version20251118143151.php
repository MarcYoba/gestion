<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251118143151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE salaire_a (id INT AUTO_INCREMENT NOT NULL, employer_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, user_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, createt_at DATE NOT NULL, status VARCHAR(255) NOT NULL, salaire_brut DOUBLE PRECISION NOT NULL, cotisation_sociales DOUBLE PRECISION NOT NULL, impots DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_A046D54041CD9E7A (employer_id), INDEX IDX_A046D540D725330D (agence_id), INDEX IDX_A046D540A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE salaire_a ADD CONSTRAINT FK_A046D54041CD9E7A FOREIGN KEY (employer_id) REFERENCES employer (id)');
        $this->addSql('ALTER TABLE salaire_a ADD CONSTRAINT FK_A046D540D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE salaire_a ADD CONSTRAINT FK_A046D540A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
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
        $this->addSql('ALTER TABLE salaire_a DROP FOREIGN KEY FK_A046D54041CD9E7A');
        $this->addSql('ALTER TABLE salaire_a DROP FOREIGN KEY FK_A046D540D725330D');
        $this->addSql('ALTER TABLE salaire_a DROP FOREIGN KEY FK_A046D540A76ED395');
        $this->addSql('DROP TABLE salaire_a');
    }
}
