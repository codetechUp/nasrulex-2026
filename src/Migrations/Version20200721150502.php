<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200721150502 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ducument');
        $this->addSql('ALTER TABLE document ADD souscat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A761371453A FOREIGN KEY (souscat_id) REFERENCES sous_categorie (id)');
        $this->addSql('CREATE INDEX IDX_D8698A761371453A ON document (souscat_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ducument (id INT AUTO_INCREMENT NOT NULL, souscat_id INT DEFAULT NULL, INDEX IDX_1BC749F61371453A (souscat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ducument ADD CONSTRAINT FK_1BC749F61371453A FOREIGN KEY (souscat_id) REFERENCES sous_categorie (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A761371453A');
        $this->addSql('DROP INDEX IDX_D8698A761371453A ON document');
        $this->addSql('ALTER TABLE document DROP souscat_id');
    }
}
