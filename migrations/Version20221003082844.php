<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221003082844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE steam_review (id INT AUTO_INCREMENT NOT NULL, hours DOUBLE PRECISION NOT NULL, recommended TINYINT(1) NOT NULL, text VARCHAR(8000) DEFAULT NULL, username VARCHAR(255) NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE team_review');
        $this->addSql('DROP INDEX `primary` ON game');
        $this->addSql('ALTER TABLE game CHANGE appid id INT NOT NULL');
        $this->addSql('ALTER TABLE game ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE review ADD owner_id INT NOT NULL, ADD game_id INT NOT NULL, CHANGE date date_updated DATETIME NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C67E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6E48FD905 FOREIGN KEY (game_id) REFERENCES `game` (id)');
        $this->addSql('CREATE INDEX IDX_794381C67E3C61F9 ON review (owner_id)');
        $this->addSql('CREATE INDEX IDX_794381C6E48FD905 ON review (game_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE team_review (id INT AUTO_INCREMENT NOT NULL, hours DOUBLE PRECISION NOT NULL, recommended TINYINT(1) NOT NULL, text VARCHAR(8000) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE steam_review');
        $this->addSql('DROP INDEX `PRIMARY` ON `game`');
        $this->addSql('ALTER TABLE `game` CHANGE id appid INT NOT NULL');
        $this->addSql('ALTER TABLE `game` ADD PRIMARY KEY (appid)');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C67E3C61F9');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6E48FD905');
        $this->addSql('DROP INDEX IDX_794381C67E3C61F9 ON review');
        $this->addSql('DROP INDEX IDX_794381C6E48FD905 ON review');
        $this->addSql('ALTER TABLE review DROP owner_id, DROP game_id, CHANGE date_updated date DATETIME NOT NULL');
    }
}
