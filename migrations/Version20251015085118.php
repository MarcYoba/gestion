<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251015085118 extends AbstractMigration
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
        $this->addSql('DROP TABLE employer_agence');
        $this->addSql('ALTER TABLE tresorerie ADD user_id INT DEFAULT NULL, ADD agence_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tresorerie ADD CONSTRAINT FK_2F2C32B8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tresorerie ADD CONSTRAINT FK_2F2C32B8D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_2F2C32B8A76ED395 ON tresorerie (user_id)');
        $this->addSql('CREATE INDEX IDX_2F2C32B8D725330D ON tresorerie (agence_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employer_agence (employer_id INT NOT NULL, agence_id INT NOT NULL, INDEX IDX_3B8E660E41CD9E7A (employer_id), INDEX IDX_3B8E660ED725330D (agence_id), PRIMARY KEY(employer_id, agence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE employer_agence ADD CONSTRAINT FK_3B8E660E41CD9E7A FOREIGN KEY (employer_id) REFERENCES employer (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employer_agence ADD CONSTRAINT FK_3B8E660ED725330D FOREIGN KEY (agence_id) REFERENCES agence (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tresorerie DROP FOREIGN KEY FK_2F2C32B8A76ED395');
        $this->addSql('ALTER TABLE tresorerie DROP FOREIGN KEY FK_2F2C32B8D725330D');
        $this->addSql('DROP INDEX IDX_2F2C32B8A76ED395 ON tresorerie');
        $this->addSql('DROP INDEX IDX_2F2C32B8D725330D ON tresorerie');
        $this->addSql('ALTER TABLE tresorerie DROP user_id, DROP agence_id');
    }
}
