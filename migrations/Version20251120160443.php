<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251120160443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE emprunt_a (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, durre VARCHAR(255) NOT NULL, tauxinteretdebiteur DOUBLE PRECISION NOT NULL, tauxannueleffectifglobal DOUBLE PRECISION NOT NULL, couttotal DOUBLE PRECISION NOT NULL, garantie VARCHAR(255) NOT NULL, identitepreteur VARCHAR(255) NOT NULL, emprunteur VARCHAR(255) NOT NULL, createt_at DATE NOT NULL, INDEX IDX_AF342698A76ED395 (user_id), INDEX IDX_AF342698D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE emprunt_a ADD CONSTRAINT FK_AF342698A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE emprunt_a ADD CONSTRAINT FK_AF342698D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
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
        $this->addSql('ALTER TABLE emprunt_a DROP FOREIGN KEY FK_AF342698A76ED395');
        $this->addSql('ALTER TABLE emprunt_a DROP FOREIGN KEY FK_AF342698D725330D');
        $this->addSql('DROP TABLE emprunt_a');
    }
}
