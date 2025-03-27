<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250327111858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat ADD fournisseur_id INT NOT NULL, ADD produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_26A98456670C757F ON achat (fournisseur_id)');
        $this->addSql('CREATE INDEX IDX_26A98456F347EFB ON achat (produit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456670C757F');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456F347EFB');
        $this->addSql('DROP INDEX IDX_26A98456670C757F ON achat');
        $this->addSql('DROP INDEX IDX_26A98456F347EFB ON achat');
        $this->addSql('ALTER TABLE achat DROP fournisseur_id, DROP produit_id');
    }
}
