<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221006135334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE metacritic CHANGE game_id game_id INT NOT NULL');
        $this->addSql('ALTER TABLE pc_requirement CHANGE game_id game_id INT NOT NULL');
        $this->addSql('ALTER TABLE platform CHANGE game_id game_id INT NOT NULL');
        $this->addSql('ALTER TABLE release_date CHANGE game_id game_id INT NOT NULL');
        $this->addSql('ALTER TABLE screenshot CHANGE game_id game_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE metacritic CHANGE game_id game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pc_requirement CHANGE game_id game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE platform CHANGE game_id game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE release_date CHANGE game_id game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE screenshot CHANGE game_id game_id INT DEFAULT NULL');
    }
}
