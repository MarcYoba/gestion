<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250314122203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_agence DROP FOREIGN KEY FK_1938194A76ED395');
        $this->addSql('ALTER TABLE user_agence DROP FOREIGN KEY FK_1938194D725330D');
        $this->addSql('DROP TABLE user_agence');
        $this->addSql('ALTER TABLE employer DROP FOREIGN KEY FK_DE4CF066786A81FB');
        $this->addSql('DROP INDEX UNIQ_DE4CF066786A81FB ON employer');
        $this->addSql('ALTER TABLE employer DROP iduser_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_agence (user_id INT NOT NULL, agence_id INT NOT NULL, INDEX IDX_1938194A76ED395 (user_id), INDEX IDX_1938194D725330D (agence_id), PRIMARY KEY(user_id, agence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_agence ADD CONSTRAINT FK_1938194A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_agence ADD CONSTRAINT FK_1938194D725330D FOREIGN KEY (agence_id) REFERENCES agence (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employer ADD iduser_id INT NOT NULL');
        $this->addSql('ALTER TABLE employer ADD CONSTRAINT FK_DE4CF066786A81FB FOREIGN KEY (iduser_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DE4CF066786A81FB ON employer (iduser_id)');
    }
}
