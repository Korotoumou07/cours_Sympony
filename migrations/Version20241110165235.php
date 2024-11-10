<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241110165235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, dette_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, date_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B1DC7A1EE11400A1 (dette_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1EE11400A1 FOREIGN KEY (dette_id) REFERENCES dette (id)');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455F2C56620');
        $this->addSql('DROP INDEX UNIQ_C7440455A76ED395 ON client');
        $this->addSql('ALTER TABLE client CHANGE compte_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455A76ED395 ON client (user_id)');
        $this->addSql('ALTER TABLE dette CHANGE montant_verser montant_verse DOUBLE PRECISION NOT NULL, CHANGE create_at date_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user DROP roles, CHANGE prenom prenom VARCHAR(25) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1EE11400A1');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455A76ED395');
        $this->addSql('DROP INDEX UNIQ_C7440455A76ED395 ON client');
        $this->addSql('ALTER TABLE client CHANGE user_id compte_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455F2C56620 FOREIGN KEY (compte_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455A76ED395 ON client (compte_id)');
        $this->addSql('ALTER TABLE dette CHANGE date_at create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE montant_verse montant_verser DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL, CHANGE prenom prenom VARCHAR(30) NOT NULL');
    }
}
