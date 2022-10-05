<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221004101259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE steam_review ADD game_id INT NOT NULL');
        $this->addSql('ALTER TABLE steam_review ADD CONSTRAINT FK_4CDEE298E48FD905 FOREIGN KEY (game_id) REFERENCES `game` (id)');
        $this->addSql('CREATE INDEX IDX_4CDEE298E48FD905 ON steam_review (game_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE steam_review DROP FOREIGN KEY FK_4CDEE298E48FD905');
        $this->addSql('DROP INDEX IDX_4CDEE298E48FD905 ON steam_review');
        $this->addSql('ALTER TABLE steam_review DROP game_id');
    }
}
