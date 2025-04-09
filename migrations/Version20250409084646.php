<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409084646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat CHANGE createdAt created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE achat RENAME INDEX idx_e768ab52a76ed395 TO IDX_26A98456A76ED395');
        $this->addSql('ALTER TABLE achat RENAME INDEX idx_e768ab52670c757f TO IDX_26A98456670C757F');
        $this->addSql('ALTER TABLE achat RENAME INDEX idx_e768ab52f347efb TO IDX_26A98456F347EFB');
        $this->addSql('ALTER TABLE agence CHANGE createdBY created_by INT NOT NULL');
        $this->addSql('ALTER TABLE agence RENAME INDEX idx_636d9f9fa76ed395 TO IDX_64C19AA9A76ED395');
        $this->addSql('ALTER TABLE clients CHANGE createdAt created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE clients RENAME INDEX uniq_cf7517e8a76ed395 TO UNIQ_C82E74A76ED395');
        $this->addSql('ALTER TABLE credit CHANGE createdAt created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE credit RENAME INDEX idx_1b6d6bc819eb6921 TO IDX_1CC16EFE19EB6921');
        $this->addSql('ALTER TABLE credit RENAME INDEX uniq_1b6d6bc87dc7170a TO UNIQ_1CC16EFE7DC7170A');
        $this->addSql('ALTER TABLE depenses CHANGE createdAt created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE imageName image_name VARCHAR(255) NOT NULL, CHANGE imageSize image_size INT NOT NULL');
        $this->addSql('ALTER TABLE employer CHANGE createdAt created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE employer RENAME INDEX uniq_273a9230a76ed395 TO UNIQ_DE4CF066A76ED395');
        $this->addSql('ALTER TABLE employer RENAME INDEX idx_273a9230d725330d TO IDX_DE4CF066D725330D');
        $this->addSql('ALTER TABLE facture CHANGE createdAt created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE facture RENAME INDEX idx_313b5d8cf347efb TO IDX_FE866410F347EFB');
        $this->addSql('ALTER TABLE facture RENAME INDEX idx_313b5d8ca76ed395 TO IDX_FE866410A76ED395');
        $this->addSql('ALTER TABLE facture RENAME INDEX idx_313b5d8c7dc7170a TO IDX_FE8664107DC7170A');
        $this->addSql('ALTER TABLE facture RENAME INDEX idx_313b5d8c19eb6921 TO IDX_FE86641019EB6921');
        $this->addSql('ALTER TABLE fournisseur CHANGE createdAt created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE fournisseur RENAME INDEX idx_b00245e5a76ed395 TO IDX_369ECA32A76ED395');
        $this->addSql('ALTER TABLE produit CHANGE createdAt created_at DATE NOT NULL');
        $this->addSql('ALTER TABLE produit RENAME INDEX idx_e618d5bba76ed395 TO IDX_29A5EC27A76ED395');
        $this->addSql('ALTER TABLE quantiteproduit CHANGE quantiteRestant quantite_restant DOUBLE PRECISION NOT NULL, CHANGE createdAt created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE quantiteproduit RENAME INDEX idx_7eff5c4cf347efb TO IDX_F63A62D2F347EFB');
        $this->addSql('ALTER TABLE quantiteproduit RENAME INDEX idx_7eff5c4c7dc7170a TO IDX_F63A62D27DC7170A');
        $this->addSql('ALTER TABLE quantiteproduit RENAME INDEX idx_7eff5c4ca76ed395 TO IDX_F63A62D2A76ED395');
        $this->addSql('ALTER TABLE salaire CHANGE createdAd created_ad DATE NOT NULL');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_2da17977e7927c74 TO UNIQ_8D93D649E7927C74');
        $this->addSql('ALTER TABLE vente CHANGE createdAt created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE vente RENAME INDEX idx_494b054819eb6921 TO IDX_888A2A4C19EB6921');
        $this->addSql('ALTER TABLE vente RENAME INDEX idx_494b0548a76ed395 TO IDX_888A2A4CA76ED395');
        $this->addSql('ALTER TABLE versement CHANGE createdAd created_ad DATE NOT NULL');
        $this->addSql('ALTER TABLE versement RENAME INDEX idx_f39f11c4ab014612 TO IDX_716E9367AB014612');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat CHANGE created_at createdAt DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE achat RENAME INDEX idx_26a98456670c757f TO IDX_E768AB52670C757F');
        $this->addSql('ALTER TABLE achat RENAME INDEX idx_26a98456a76ed395 TO IDX_E768AB52A76ED395');
        $this->addSql('ALTER TABLE achat RENAME INDEX idx_26a98456f347efb TO IDX_E768AB52F347EFB');
        $this->addSql('ALTER TABLE agence CHANGE created_by createdBY INT NOT NULL');
        $this->addSql('ALTER TABLE agence RENAME INDEX idx_64c19aa9a76ed395 TO IDX_636D9F9FA76ED395');
        $this->addSql('ALTER TABLE clients CHANGE created_at createdAt DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE clients RENAME INDEX uniq_c82e74a76ed395 TO UNIQ_CF7517E8A76ED395');
        $this->addSql('ALTER TABLE credit CHANGE created_at createdAt DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE credit RENAME INDEX idx_1cc16efe19eb6921 TO IDX_1B6D6BC819EB6921');
        $this->addSql('ALTER TABLE credit RENAME INDEX uniq_1cc16efe7dc7170a TO UNIQ_1B6D6BC87DC7170A');
        $this->addSql('ALTER TABLE depenses CHANGE created_at createdAt DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE image_name imageName VARCHAR(255) NOT NULL, CHANGE image_size imageSize INT NOT NULL');
        $this->addSql('ALTER TABLE employer CHANGE created_at createdAt DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE employer RENAME INDEX idx_de4cf066d725330d TO IDX_273A9230D725330D');
        $this->addSql('ALTER TABLE employer RENAME INDEX uniq_de4cf066a76ed395 TO UNIQ_273A9230A76ED395');
        $this->addSql('ALTER TABLE facture CHANGE created_at createdAt DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE facture RENAME INDEX idx_fe86641019eb6921 TO IDX_313B5D8C19EB6921');
        $this->addSql('ALTER TABLE facture RENAME INDEX idx_fe8664107dc7170a TO IDX_313B5D8C7DC7170A');
        $this->addSql('ALTER TABLE facture RENAME INDEX idx_fe866410a76ed395 TO IDX_313B5D8CA76ED395');
        $this->addSql('ALTER TABLE facture RENAME INDEX idx_fe866410f347efb TO IDX_313B5D8CF347EFB');
        $this->addSql('ALTER TABLE fournisseur CHANGE created_at createdAt DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE fournisseur RENAME INDEX idx_369eca32a76ed395 TO IDX_B00245E5A76ED395');
        $this->addSql('ALTER TABLE produit CHANGE created_at createdAt DATE NOT NULL');
        $this->addSql('ALTER TABLE produit RENAME INDEX idx_29a5ec27a76ed395 TO IDX_E618D5BBA76ED395');
        $this->addSql('ALTER TABLE quantiteproduit CHANGE quantite_restant quantiteRestant DOUBLE PRECISION NOT NULL, CHANGE created_at createdAt DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE quantiteproduit RENAME INDEX idx_f63a62d27dc7170a TO IDX_7EFF5C4C7DC7170A');
        $this->addSql('ALTER TABLE quantiteproduit RENAME INDEX idx_f63a62d2a76ed395 TO IDX_7EFF5C4CA76ED395');
        $this->addSql('ALTER TABLE quantiteproduit RENAME INDEX idx_f63a62d2f347efb TO IDX_7EFF5C4CF347EFB');
        $this->addSql('ALTER TABLE salaire CHANGE created_ad createdAd DATE NOT NULL');
        $this->addSql('ALTER TABLE user DROP created_at');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649e7927c74 TO UNIQ_2DA17977E7927C74');
        $this->addSql('ALTER TABLE vente CHANGE created_at createdAt DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE vente RENAME INDEX idx_888a2a4c19eb6921 TO IDX_494B054819EB6921');
        $this->addSql('ALTER TABLE vente RENAME INDEX idx_888a2a4ca76ed395 TO IDX_494B0548A76ED395');
        $this->addSql('ALTER TABLE versement CHANGE created_ad createdAd DATE NOT NULL');
        $this->addSql('ALTER TABLE versement RENAME INDEX idx_716e9367ab014612 TO IDX_F39F11C4AB014612');
    }
}
