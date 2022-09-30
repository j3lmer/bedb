<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220930122753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, appid INT NOT NULL, name VARCHAR(255) NOT NULL, detailed_description VARCHAR(1000) DEFAULT NULL, about VARCHAR(1000) DEFAULT NULL, short_description VARCHAR(500) DEFAULT NULL, supported_languages VARCHAR(1000) DEFAULT NULL, header_image VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, developers VARCHAR(255) NOT NULL, publishers VARCHAR(255) NOT NULL, recommendations_total INT NOT NULL, notes VARCHAR(255) DEFAULT NULL, nsfw TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON `user`');
    }
}
