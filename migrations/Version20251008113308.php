<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251008113308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prospection_a (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, speculation VARCHAR(255) NOT NULL, sujet VARCHAR(255) NOT NULL, souche VARCHAR(255) NOT NULL, ravitaillement VARCHAR(255) NOT NULL, commentaire VARCHAR(255) NOT NULL, createt_at DATE NOT NULL, INDEX IDX_535E09BBA76ED395 (user_id), INDEX IDX_535E09BBD725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prospection_a ADD CONSTRAINT FK_535E09BBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prospection_a ADD CONSTRAINT FK_535E09BBD725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
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
        $this->addSql('ALTER TABLE prospection_a DROP FOREIGN KEY FK_535E09BBA76ED395');
        $this->addSql('ALTER TABLE prospection_a DROP FOREIGN KEY FK_535E09BBD725330D');
        $this->addSql('DROP TABLE prospection_a');
    }
}
