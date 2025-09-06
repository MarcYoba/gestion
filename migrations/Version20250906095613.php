<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250906095613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achat (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, fournisseur_id INT NOT NULL, produit_id INT NOT NULL, agence_id INT NOT NULL, prix DOUBLE PRECISION NOT NULL, quantite DOUBLE PRECISION NOT NULL, montant DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_26A98456A76ED395 (user_id), INDEX IDX_26A98456670C757F (fournisseur_id), INDEX IDX_26A98456F347EFB (produit_id), INDEX IDX_26A98456D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE achat_a (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, forunisseur_id INT NOT NULL, user_id INT NOT NULL, agence_id INT DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, quantite DOUBLE PRECISION NOT NULL, montant DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_407B99E8F347EFB (produit_id), INDEX IDX_407B99E83AAF8973 (forunisseur_id), INDEX IDX_407B99E8A76ED395 (user_id), INDEX IDX_407B99E8D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actif (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, brut DOUBLE PRECISION NOT NULL, amortissement DOUBLE PRECISION NOT NULL, net DOUBLE PRECISION NOT NULL, created DATE NOT NULL, cathegorie VARCHAR(255) NOT NULL, ordre INT NOT NULL, INDEX IDX_8F52502A76ED395 (user_id), INDEX IDX_8F52502D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agence (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, datecreation DATE NOT NULL, created_by INT NOT NULL, adress VARCHAR(255) NOT NULL, INDEX IDX_64C19AA9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE caisse (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, operation VARCHAR(255) NOT NULL, motif VARCHAR(255) NOT NULL, create_at DATE NOT NULL, INDEX IDX_B2A353C8A76ED395 (user_id), INDEX IDX_B2A353C8D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE caisse_a (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, operation VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, motif VARCHAR(255) NOT NULL, create_at DATE NOT NULL, INDEX IDX_B70EED01A76ED395 (user_id), INDEX IDX_B70EED01D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clients (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', telephone VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_C82E74A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE credit (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, vente_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1CC16EFE19EB6921 (client_id), UNIQUE INDEX UNIQ_1CC16EFE7DC7170A (vente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depense_a (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', type VARCHAR(255) NOT NULL, INDEX IDX_DDDEFBDBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depenses (id INT AUTO_INCREMENT NOT NULL, agence_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', type VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, image_name VARCHAR(255) NOT NULL, image_size INT NOT NULL, INDEX IDX_EE350ECBD725330D (agence_id), INDEX IDX_EE350ECBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, agence_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', poste VARCHAR(100) NOT NULL, nom VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_DE4CF066A76ED395 (user_id), INDEX IDX_DE4CF066D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, user_id INT NOT NULL, vente_id INT NOT NULL, client_id INT NOT NULL, agence_id INT DEFAULT NULL, quantite DOUBLE PRECISION NOT NULL, prix DOUBLE PRECISION NOT NULL, typepaiement VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', montant DOUBLE PRECISION NOT NULL, INDEX IDX_FE866410F347EFB (produit_id), INDEX IDX_FE866410A76ED395 (user_id), INDEX IDX_FE8664107DC7170A (vente_id), INDEX IDX_FE86641019EB6921 (client_id), INDEX IDX_FE866410D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture_a (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, client_id INT DEFAULT NULL, user_id INT DEFAULT NULL, vente_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, quantite DOUBLE PRECISION NOT NULL, prix DOUBLE PRECISION NOT NULL, type VARCHAR(100) NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', montant DOUBLE PRECISION NOT NULL, INDEX IDX_46524B3CF347EFB (produit_id), INDEX IDX_46524B3C19EB6921 (client_id), INDEX IDX_46524B3CA76ED395 (user_id), INDEX IDX_46524B3C7DC7170A (vente_id), INDEX IDX_46524B3CD725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, agence_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(30) NOT NULL, address VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', numfacture INT NOT NULL, datefacture DATE NOT NULL, INDEX IDX_369ECA32A76ED395 (user_id), INDEX IDX_369ECA32D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur_a (id INT AUTO_INCREMENT NOT NULL, agence_id INT DEFAULT NULL, user_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, adress VARCHAR(100) NOT NULL, telephone VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_achat DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', numfacture INT NOT NULL, INDEX IDX_D08E4BEBD725330D (agence_id), INDEX IDX_D08E4BEBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historique (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, agance_id INT DEFAULT NULL, quantite DOUBLE PRECISION NOT NULL, created_at DATE NOT NULL, heurecameroun VARCHAR(255) NOT NULL, heureserver VARCHAR(255) DEFAULT NULL, INDEX IDX_EDBFD5ECF347EFB (produit_id), INDEX IDX_EDBFD5EC236A171E (agance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historique_a (id INT AUTO_INCREMENT NOT NULL, produit_a_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, quantite DOUBLE PRECISION NOT NULL, createt_ad DATE NOT NULL, heurecameroun VARCHAR(255) NOT NULL, heureserver VARCHAR(255) NOT NULL, INDEX IDX_423D75AE12462EB7 (produit_a_id), INDEX IDX_423D75AED725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poussin (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, quantite DOUBLE PRECISION NOT NULL, prix DOUBLE PRECISION NOT NULL, montant DOUBLE PRECISION NOT NULL, souche VARCHAR(255) NOT NULL, mobilepay DOUBLE PRECISION NOT NULL, credit DOUBLE PRECISION NOT NULL, cash DOUBLE PRECISION NOT NULL, reste DOUBLE PRECISION NOT NULL, status VARCHAR(50) NOT NULL, datecommande DATE NOT NULL, datelivaison DATE NOT NULL, daterapelle DATE NOT NULL, INDEX IDX_889C98AF19EB6921 (client_id), INDEX IDX_889C98AFD725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, agence_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, prixvente DOUBLE PRECISION NOT NULL, quantite DOUBLE PRECISION NOT NULL, prixachat DOUBLE PRECISION NOT NULL, gain DOUBLE PRECISION NOT NULL, stockdebut DOUBLE PRECISION NOT NULL, cathegorie VARCHAR(255) NOT NULL, created_at DATE NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_29A5EC27A76ED395 (user_id), INDEX IDX_29A5EC27D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_a (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, agence_id INT NOT NULL, nom VARCHAR(100) NOT NULL, prixvente DOUBLE PRECISION NOT NULL, prixachat DOUBLE PRECISION NOT NULL, quantite DOUBLE PRECISION NOT NULL, gain DOUBLE PRECISION NOT NULL, stockdebut DOUBLE PRECISION NOT NULL, cathegorie VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', type VARCHAR(100) NOT NULL, INDEX IDX_35363739A76ED395 (user_id), INDEX IDX_35363739D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quantiteproduit (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, vente_id INT NOT NULL, user_id INT NOT NULL, quantite_restant DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F63A62D2F347EFB (produit_id), INDEX IDX_F63A62D27DC7170A (vente_id), INDEX IDX_F63A62D2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quantiteproduit_a (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, vente_id INT DEFAULT NULL, user_id INT DEFAULT NULL, quantite DOUBLE PRECISION NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_56FD5379F347EFB (produit_id), INDEX IDX_56FD53797DC7170A (vente_id), INDEX IDX_56FD5379A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salaire (id INT AUTO_INCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, created_ad DATE NOT NULL, status VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE temp_agence (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, agence_id INT NOT NULL, INDEX IDX_13500A9EA76ED395 (user_id), INDEX IDX_13500A9ED725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(100) NOT NULL, telephone VARCHAR(20) DEFAULT NULL, speculation VARCHAR(255) DEFAULT NULL, localisation VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_login DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vente (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT NOT NULL, agence_id INT DEFAULT NULL, type VARCHAR(50) NOT NULL, quantite DOUBLE PRECISION NOT NULL, prix DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', esperce VARCHAR(255) NOT NULL, aliment VARCHAR(50) NOT NULL, heure VARCHAR(20) NOT NULL, statusvente VARCHAR(30) NOT NULL, montantcredit DOUBLE PRECISION NOT NULL, montantcash DOUBLE PRECISION NOT NULL, montantbanque DOUBLE PRECISION NOT NULL, montantmomo DOUBLE PRECISION NOT NULL, reduction DOUBLE PRECISION NOT NULL, INDEX IDX_888A2A4C19EB6921 (client_id), INDEX IDX_888A2A4CA76ED395 (user_id), INDEX IDX_888A2A4CD725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vente_a (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, user_id INT DEFAULT NULL, produit_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, type VARCHAR(100) NOT NULL, quantite DOUBLE PRECISION NOT NULL, prix DOUBLE PRECISION NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', cash DOUBLE PRECISION NOT NULL, reduction DOUBLE PRECISION NOT NULL, banque DOUBLE PRECISION NOT NULL, statut VARCHAR(100) NOT NULL, credit DOUBLE PRECISION NOT NULL, heure VARCHAR(100) NOT NULL, momo DOUBLE PRECISION NOT NULL, INDEX IDX_C13843FF19EB6921 (client_id), INDEX IDX_C13843FFA76ED395 (user_id), INDEX IDX_C13843FFF347EFB (produit_id), INDEX IDX_C13843FFD725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE versement (id INT AUTO_INCREMENT NOT NULL, clients_id INT NOT NULL, user_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, om DOUBLE PRECISION NOT NULL, banque DOUBLE PRECISION NOT NULL, created_ad DATE NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_716E9367AB014612 (clients_id), INDEX IDX_716E9367A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE versement_a (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, user_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, om DOUBLE PRECISION NOT NULL, banque DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description VARCHAR(255) NOT NULL, INDEX IDX_5F54C5A19EB6921 (client_id), INDEX IDX_5F54C5AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE achat_a ADD CONSTRAINT FK_407B99E8F347EFB FOREIGN KEY (produit_id) REFERENCES produit_a (id)');
        $this->addSql('ALTER TABLE achat_a ADD CONSTRAINT FK_407B99E83AAF8973 FOREIGN KEY (forunisseur_id) REFERENCES fournisseur_a (id)');
        $this->addSql('ALTER TABLE achat_a ADD CONSTRAINT FK_407B99E8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE achat_a ADD CONSTRAINT FK_407B99E8D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE actif ADD CONSTRAINT FK_8F52502A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE actif ADD CONSTRAINT FK_8F52502D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE agence ADD CONSTRAINT FK_64C19AA9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE caisse ADD CONSTRAINT FK_B2A353C8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE caisse ADD CONSTRAINT FK_B2A353C8D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE caisse_a ADD CONSTRAINT FK_B70EED01A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE caisse_a ADD CONSTRAINT FK_B70EED01D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE clients ADD CONSTRAINT FK_C82E74A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFE19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFE7DC7170A FOREIGN KEY (vente_id) REFERENCES vente (id)');
        $this->addSql('ALTER TABLE depense_a ADD CONSTRAINT FK_DDDEFBDBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE depenses ADD CONSTRAINT FK_EE350ECBD725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE depenses ADD CONSTRAINT FK_EE350ECBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE employer ADD CONSTRAINT FK_DE4CF066A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE employer ADD CONSTRAINT FK_DE4CF066D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664107DC7170A FOREIGN KEY (vente_id) REFERENCES vente (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641019EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE facture_a ADD CONSTRAINT FK_46524B3CF347EFB FOREIGN KEY (produit_id) REFERENCES produit_a (id)');
        $this->addSql('ALTER TABLE facture_a ADD CONSTRAINT FK_46524B3C19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE facture_a ADD CONSTRAINT FK_46524B3CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE facture_a ADD CONSTRAINT FK_46524B3C7DC7170A FOREIGN KEY (vente_id) REFERENCES vente_a (id)');
        $this->addSql('ALTER TABLE facture_a ADD CONSTRAINT FK_46524B3CD725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE fournisseur ADD CONSTRAINT FK_369ECA32A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE fournisseur ADD CONSTRAINT FK_369ECA32D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE fournisseur_a ADD CONSTRAINT FK_D08E4BEBD725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE fournisseur_a ADD CONSTRAINT FK_D08E4BEBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5ECF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5EC236A171E FOREIGN KEY (agance_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE historique_a ADD CONSTRAINT FK_423D75AE12462EB7 FOREIGN KEY (produit_a_id) REFERENCES produit_a (id)');
        $this->addSql('ALTER TABLE historique_a ADD CONSTRAINT FK_423D75AED725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE poussin ADD CONSTRAINT FK_889C98AF19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE poussin ADD CONSTRAINT FK_889C98AFD725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE produit_a ADD CONSTRAINT FK_35363739A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE produit_a ADD CONSTRAINT FK_35363739D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE quantiteproduit ADD CONSTRAINT FK_F63A62D2F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE quantiteproduit ADD CONSTRAINT FK_F63A62D27DC7170A FOREIGN KEY (vente_id) REFERENCES vente (id)');
        $this->addSql('ALTER TABLE quantiteproduit ADD CONSTRAINT FK_F63A62D2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quantiteproduit_a ADD CONSTRAINT FK_56FD5379F347EFB FOREIGN KEY (produit_id) REFERENCES produit_a (id)');
        $this->addSql('ALTER TABLE quantiteproduit_a ADD CONSTRAINT FK_56FD53797DC7170A FOREIGN KEY (vente_id) REFERENCES vente_a (id)');
        $this->addSql('ALTER TABLE quantiteproduit_a ADD CONSTRAINT FK_56FD5379A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE temp_agence ADD CONSTRAINT FK_13500A9EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE temp_agence ADD CONSTRAINT FK_13500A9ED725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE vente ADD CONSTRAINT FK_888A2A4C19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE vente ADD CONSTRAINT FK_888A2A4CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vente ADD CONSTRAINT FK_888A2A4CD725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE vente_a ADD CONSTRAINT FK_C13843FF19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE vente_a ADD CONSTRAINT FK_C13843FFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vente_a ADD CONSTRAINT FK_C13843FFF347EFB FOREIGN KEY (produit_id) REFERENCES produit_a (id)');
        $this->addSql('ALTER TABLE vente_a ADD CONSTRAINT FK_C13843FFD725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE versement ADD CONSTRAINT FK_716E9367AB014612 FOREIGN KEY (clients_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE versement ADD CONSTRAINT FK_716E9367A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE versement_a ADD CONSTRAINT FK_5F54C5A19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE versement_a ADD CONSTRAINT FK_5F54C5AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456A76ED395');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456670C757F');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456F347EFB');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456D725330D');
        $this->addSql('ALTER TABLE achat_a DROP FOREIGN KEY FK_407B99E8F347EFB');
        $this->addSql('ALTER TABLE achat_a DROP FOREIGN KEY FK_407B99E83AAF8973');
        $this->addSql('ALTER TABLE achat_a DROP FOREIGN KEY FK_407B99E8A76ED395');
        $this->addSql('ALTER TABLE achat_a DROP FOREIGN KEY FK_407B99E8D725330D');
        $this->addSql('ALTER TABLE actif DROP FOREIGN KEY FK_8F52502A76ED395');
        $this->addSql('ALTER TABLE actif DROP FOREIGN KEY FK_8F52502D725330D');
        $this->addSql('ALTER TABLE agence DROP FOREIGN KEY FK_64C19AA9A76ED395');
        $this->addSql('ALTER TABLE caisse DROP FOREIGN KEY FK_B2A353C8A76ED395');
        $this->addSql('ALTER TABLE caisse DROP FOREIGN KEY FK_B2A353C8D725330D');
        $this->addSql('ALTER TABLE caisse_a DROP FOREIGN KEY FK_B70EED01A76ED395');
        $this->addSql('ALTER TABLE caisse_a DROP FOREIGN KEY FK_B70EED01D725330D');
        $this->addSql('ALTER TABLE clients DROP FOREIGN KEY FK_C82E74A76ED395');
        $this->addSql('ALTER TABLE credit DROP FOREIGN KEY FK_1CC16EFE19EB6921');
        $this->addSql('ALTER TABLE credit DROP FOREIGN KEY FK_1CC16EFE7DC7170A');
        $this->addSql('ALTER TABLE depense_a DROP FOREIGN KEY FK_DDDEFBDBA76ED395');
        $this->addSql('ALTER TABLE depenses DROP FOREIGN KEY FK_EE350ECBD725330D');
        $this->addSql('ALTER TABLE depenses DROP FOREIGN KEY FK_EE350ECBA76ED395');
        $this->addSql('ALTER TABLE employer DROP FOREIGN KEY FK_DE4CF066A76ED395');
        $this->addSql('ALTER TABLE employer DROP FOREIGN KEY FK_DE4CF066D725330D');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410F347EFB');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410A76ED395');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664107DC7170A');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641019EB6921');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410D725330D');
        $this->addSql('ALTER TABLE facture_a DROP FOREIGN KEY FK_46524B3CF347EFB');
        $this->addSql('ALTER TABLE facture_a DROP FOREIGN KEY FK_46524B3C19EB6921');
        $this->addSql('ALTER TABLE facture_a DROP FOREIGN KEY FK_46524B3CA76ED395');
        $this->addSql('ALTER TABLE facture_a DROP FOREIGN KEY FK_46524B3C7DC7170A');
        $this->addSql('ALTER TABLE facture_a DROP FOREIGN KEY FK_46524B3CD725330D');
        $this->addSql('ALTER TABLE fournisseur DROP FOREIGN KEY FK_369ECA32A76ED395');
        $this->addSql('ALTER TABLE fournisseur DROP FOREIGN KEY FK_369ECA32D725330D');
        $this->addSql('ALTER TABLE fournisseur_a DROP FOREIGN KEY FK_D08E4BEBD725330D');
        $this->addSql('ALTER TABLE fournisseur_a DROP FOREIGN KEY FK_D08E4BEBA76ED395');
        $this->addSql('ALTER TABLE historique DROP FOREIGN KEY FK_EDBFD5ECF347EFB');
        $this->addSql('ALTER TABLE historique DROP FOREIGN KEY FK_EDBFD5EC236A171E');
        $this->addSql('ALTER TABLE historique_a DROP FOREIGN KEY FK_423D75AE12462EB7');
        $this->addSql('ALTER TABLE historique_a DROP FOREIGN KEY FK_423D75AED725330D');
        $this->addSql('ALTER TABLE poussin DROP FOREIGN KEY FK_889C98AF19EB6921');
        $this->addSql('ALTER TABLE poussin DROP FOREIGN KEY FK_889C98AFD725330D');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27A76ED395');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27D725330D');
        $this->addSql('ALTER TABLE produit_a DROP FOREIGN KEY FK_35363739A76ED395');
        $this->addSql('ALTER TABLE produit_a DROP FOREIGN KEY FK_35363739D725330D');
        $this->addSql('ALTER TABLE quantiteproduit DROP FOREIGN KEY FK_F63A62D2F347EFB');
        $this->addSql('ALTER TABLE quantiteproduit DROP FOREIGN KEY FK_F63A62D27DC7170A');
        $this->addSql('ALTER TABLE quantiteproduit DROP FOREIGN KEY FK_F63A62D2A76ED395');
        $this->addSql('ALTER TABLE quantiteproduit_a DROP FOREIGN KEY FK_56FD5379F347EFB');
        $this->addSql('ALTER TABLE quantiteproduit_a DROP FOREIGN KEY FK_56FD53797DC7170A');
        $this->addSql('ALTER TABLE quantiteproduit_a DROP FOREIGN KEY FK_56FD5379A76ED395');
        $this->addSql('ALTER TABLE temp_agence DROP FOREIGN KEY FK_13500A9EA76ED395');
        $this->addSql('ALTER TABLE temp_agence DROP FOREIGN KEY FK_13500A9ED725330D');
        $this->addSql('ALTER TABLE vente DROP FOREIGN KEY FK_888A2A4C19EB6921');
        $this->addSql('ALTER TABLE vente DROP FOREIGN KEY FK_888A2A4CA76ED395');
        $this->addSql('ALTER TABLE vente DROP FOREIGN KEY FK_888A2A4CD725330D');
        $this->addSql('ALTER TABLE vente_a DROP FOREIGN KEY FK_C13843FF19EB6921');
        $this->addSql('ALTER TABLE vente_a DROP FOREIGN KEY FK_C13843FFA76ED395');
        $this->addSql('ALTER TABLE vente_a DROP FOREIGN KEY FK_C13843FFF347EFB');
        $this->addSql('ALTER TABLE vente_a DROP FOREIGN KEY FK_C13843FFD725330D');
        $this->addSql('ALTER TABLE versement DROP FOREIGN KEY FK_716E9367AB014612');
        $this->addSql('ALTER TABLE versement DROP FOREIGN KEY FK_716E9367A76ED395');
        $this->addSql('ALTER TABLE versement_a DROP FOREIGN KEY FK_5F54C5A19EB6921');
        $this->addSql('ALTER TABLE versement_a DROP FOREIGN KEY FK_5F54C5AA76ED395');
        $this->addSql('DROP TABLE achat');
        $this->addSql('DROP TABLE achat_a');
        $this->addSql('DROP TABLE actif');
        $this->addSql('DROP TABLE agence');
        $this->addSql('DROP TABLE caisse');
        $this->addSql('DROP TABLE caisse_a');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE credit');
        $this->addSql('DROP TABLE depense_a');
        $this->addSql('DROP TABLE depenses');
        $this->addSql('DROP TABLE employer');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE facture_a');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE fournisseur_a');
        $this->addSql('DROP TABLE historique');
        $this->addSql('DROP TABLE historique_a');
        $this->addSql('DROP TABLE poussin');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produit_a');
        $this->addSql('DROP TABLE quantiteproduit');
        $this->addSql('DROP TABLE quantiteproduit_a');
        $this->addSql('DROP TABLE salaire');
        $this->addSql('DROP TABLE temp_agence');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vente');
        $this->addSql('DROP TABLE vente_a');
        $this->addSql('DROP TABLE versement');
        $this->addSql('DROP TABLE versement_a');
    }
}
