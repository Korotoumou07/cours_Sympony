<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241110153416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client CHANGE surname surname VARCHAR(50) NOT NULL, CHANGE telephone telephone VARCHAR(11) NOT NULL, CHANGE adresse adresse LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE client RENAME INDEX uniq_c7440455f2c56620 TO UNIQ_C7440455A76ED395');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL, CHANGE nom nom VARCHAR(25) NOT NULL, CHANGE prenom prenom VARCHAR(30) NOT NULL, CHANGE login login VARCHAR(180) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649aa08cb10 TO UNIQ_IDENTIFIER_LOGIN');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client CHANGE surname surname VARCHAR(20) NOT NULL, CHANGE telephone telephone VARCHAR(9) NOT NULL, CHANGE adresse adresse VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE client RENAME INDEX uniq_c7440455a76ed395 TO UNIQ_C7440455F2C56620');
        $this->addSql('ALTER TABLE user DROP roles, CHANGE login login VARCHAR(25) NOT NULL, CHANGE password password VARCHAR(15) NOT NULL, CHANGE nom nom VARCHAR(50) NOT NULL, CHANGE prenom prenom VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_identifier_login TO UNIQ_8D93D649AA08CB10');
    }
}
