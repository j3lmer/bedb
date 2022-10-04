<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221004103722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE developer (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_65FB8B9AE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publisher (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_9CE8D546E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE developer ADD CONSTRAINT FK_65FB8B9AE48FD905 FOREIGN KEY (game_id) REFERENCES `game` (id)');
        $this->addSql('ALTER TABLE publisher ADD CONSTRAINT FK_9CE8D546E48FD905 FOREIGN KEY (game_id) REFERENCES `game` (id)');
        $this->addSql('ALTER TABLE game DROP developers, DROP publishers');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE developer DROP FOREIGN KEY FK_65FB8B9AE48FD905');
        $this->addSql('ALTER TABLE publisher DROP FOREIGN KEY FK_9CE8D546E48FD905');
        $this->addSql('DROP TABLE developer');
        $this->addSql('DROP TABLE publisher');
        $this->addSql('ALTER TABLE `game` ADD developers VARCHAR(255) NOT NULL, ADD publishers VARCHAR(255) NOT NULL');
    }
}
