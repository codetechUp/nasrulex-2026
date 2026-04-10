<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200731202215 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE packs (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, price INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE packs_categorie (packs_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_AA0FE22F64BB2150 (packs_id), INDEX IDX_AA0FE22FBCF5E72D (categorie_id), PRIMARY KEY(packs_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, pack_id INT DEFAULT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, INDEX IDX_A3C664D3A76ED395 (user_id), INDEX IDX_A3C664D31919B217 (pack_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE packs_categorie ADD CONSTRAINT FK_AA0FE22F64BB2150 FOREIGN KEY (packs_id) REFERENCES packs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE packs_categorie ADD CONSTRAINT FK_AA0FE22FBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D31919B217 FOREIGN KEY (pack_id) REFERENCES packs (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE packs_categorie DROP FOREIGN KEY FK_AA0FE22F64BB2150');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D31919B217');
        $this->addSql('DROP TABLE packs');
        $this->addSql('DROP TABLE packs_categorie');
        $this->addSql('DROP TABLE subscription');
    }
}
