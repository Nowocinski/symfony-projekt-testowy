<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191111103756 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE kategoria (id INT AUTO_INCREMENT NOT NULL, nazwa VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produkt (id INT AUTO_INCREMENT NOT NULL, kategoria_id INT DEFAULT NULL, nazwa TINYTEXT NOT NULL, cena INT NOT NULL, INDEX IDX_1B938EA5359B0684 (kategoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produkt ADD CONSTRAINT FK_1B938EA5359B0684 FOREIGN KEY (kategoria_id) REFERENCES kategoria (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produkt DROP FOREIGN KEY FK_1B938EA5359B0684');
        $this->addSql('DROP TABLE kategoria');
        $this->addSql('DROP TABLE produkt');
    }
}
