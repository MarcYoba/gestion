<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250504115901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE poussin DROP FOREIGN KEY FK_889C98AF19EB6921');
        $this->addSql('ALTER TABLE poussin DROP FOREIGN KEY FK_889C98AFA76ED395');
        $this->addSql('DROP TABLE poussin');
        $this->addSql('ALTER TABLE depenses ADD agence_id INT NOT NULL');
        $this->addSql('ALTER TABLE depenses ADD CONSTRAINT FK_EE350ECBD725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_EE350ECBD725330D ON depenses (agence_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE poussin (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT NOT NULL, quantite INT NOT NULL, prix INT NOT NULL, montant INT NOT NULL, souches VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, momo INT NOT NULL, credit INT NOT NULL, cash INT NOT NULL, reste DOUBLE PRECISION NOT NULL, status VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_livraison DATE NOT NULL, daterapelle DATE NOT NULL, INDEX IDX_889C98AF19EB6921 (client_id), INDEX IDX_889C98AFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE poussin ADD CONSTRAINT FK_889C98AF19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE poussin ADD CONSTRAINT FK_889C98AFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE depenses DROP FOREIGN KEY FK_EE350ECBD725330D');
        $this->addSql('DROP INDEX IDX_EE350ECBD725330D ON depenses');
        $this->addSql('ALTER TABLE depenses DROP agence_id');
    }
}
