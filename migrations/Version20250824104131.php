<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250824104131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employer_agence DROP FOREIGN KEY FK_3B8E660E41CD9E7A');
        $this->addSql('ALTER TABLE employer_agence DROP FOREIGN KEY FK_3B8E660ED725330D');
        $this->addSql('ALTER TABLE user_agence DROP FOREIGN KEY FK_1938194A76ED395');
        $this->addSql('ALTER TABLE user_agence DROP FOREIGN KEY FK_1938194D725330D');
        $this->addSql('DROP TABLE employer_agence');
        $this->addSql('DROP TABLE user_agence');
        $this->addSql('ALTER TABLE poussin ADD client_id INT DEFAULT NULL, ADD quantite DOUBLE PRECISION NOT NULL, ADD prix DOUBLE PRECISION NOT NULL, ADD montant DOUBLE PRECISION NOT NULL, ADD souche VARCHAR(255) NOT NULL, ADD mobilepay DOUBLE PRECISION NOT NULL, ADD credit DOUBLE PRECISION NOT NULL, ADD cash DOUBLE PRECISION NOT NULL, ADD reste DOUBLE PRECISION NOT NULL, ADD status VARCHAR(50) NOT NULL, ADD datecommande DATE NOT NULL, ADD datelivaison DATE NOT NULL, ADD daterapelle DATE NOT NULL');
        $this->addSql('ALTER TABLE poussin ADD CONSTRAINT FK_889C98AF19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('CREATE INDEX IDX_889C98AF19EB6921 ON poussin (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employer_agence (employer_id INT NOT NULL, agence_id INT NOT NULL, INDEX IDX_3B8E660E41CD9E7A (employer_id), INDEX IDX_3B8E660ED725330D (agence_id), PRIMARY KEY(employer_id, agence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_agence (user_id INT NOT NULL, agence_id INT NOT NULL, INDEX IDX_1938194A76ED395 (user_id), INDEX IDX_1938194D725330D (agence_id), PRIMARY KEY(user_id, agence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE employer_agence ADD CONSTRAINT FK_3B8E660E41CD9E7A FOREIGN KEY (employer_id) REFERENCES employer (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employer_agence ADD CONSTRAINT FK_3B8E660ED725330D FOREIGN KEY (agence_id) REFERENCES agence (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_agence ADD CONSTRAINT FK_1938194A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_agence ADD CONSTRAINT FK_1938194D725330D FOREIGN KEY (agence_id) REFERENCES agence (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE poussin DROP FOREIGN KEY FK_889C98AF19EB6921');
        $this->addSql('DROP INDEX IDX_889C98AF19EB6921 ON poussin');
        $this->addSql('ALTER TABLE poussin DROP client_id, DROP quantite, DROP prix, DROP montant, DROP souche, DROP mobilepay, DROP credit, DROP cash, DROP reste, DROP status, DROP datecommande, DROP datelivaison, DROP daterapelle');
    }
}
