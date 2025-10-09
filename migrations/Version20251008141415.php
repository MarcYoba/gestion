<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251008141415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE autopsie (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, famille VARCHAR(255) NOT NULL, espece VARCHAR(255) NOT NULL, race VARCHAR(255) NOT NULL, age VARCHAR(255) NOT NULL, origine VARCHAR(255) NOT NULL, effectif VARCHAR(255) NOT NULL, morbidite VARCHAR(255) NOT NULL, mortalite VARCHAR(255) NOT NULL, clinique VARCHAR(255) NOT NULL, traitement VARCHAR(255) NOT NULL, pathologiques VARCHAR(255) NOT NULL, antecedent VARCHAR(255) NOT NULL, vaccinations VARCHAR(255) NOT NULL, embonpoint VARCHAR(255) NOT NULL, mort VARCHAR(255) NOT NULL, datemort DATE NOT NULL, lieu VARCHAR(255) NOT NULL, conservation VARCHAR(255) NOT NULL, durreconservation VARCHAR(255) NOT NULL, dateautopsie DATE NOT NULL, medecin VARCHAR(255) NOT NULL, appendices VARCHAR(255) NOT NULL, muqueuses VARCHAR(255) NOT NULL, peau VARCHAR(255) NOT NULL, membre VARCHAR(255) NOT NULL, anomalies VARCHAR(255) NOT NULL, tissu VARCHAR(255) NOT NULL, tube VARCHAR(255) NOT NULL, respiratoire VARCHAR(255) NOT NULL, circulatoire VARCHAR(255) NOT NULL, genital VARCHAR(255) NOT NULL, urinaire VARCHAR(255) NOT NULL, locomoteur VARCHAR(255) NOT NULL, nerveux VARCHAR(255) NOT NULL, endocrines VARCHAR(255) NOT NULL, glandes VARCHAR(255) NOT NULL, hemato VARCHAR(255) NOT NULL, diagnostic VARCHAR(255) NOT NULL, certitude VARCHAR(255) NOT NULL, INDEX IDX_F88D1C2719EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE autopsie ADD CONSTRAINT FK_F88D1C2719EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
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
        $this->addSql('ALTER TABLE autopsie DROP FOREIGN KEY FK_F88D1C2719EB6921');
        $this->addSql('DROP TABLE autopsie');
    }
}
