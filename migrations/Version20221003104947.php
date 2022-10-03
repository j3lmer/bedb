<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221003104947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_835033F8E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE genre ADD CONSTRAINT FK_835033F8E48FD905 FOREIGN KEY (game_id) REFERENCES `game` (id)');
        $this->addSql('DROP TABLE genres');
        $this->addSql('ALTER TABLE category ADD game_id INT NOT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1E48FD905 FOREIGN KEY (game_id) REFERENCES `game` (id)');
        $this->addSql('CREATE INDEX IDX_64C19C1E48FD905 ON category (game_id)');
        $this->addSql('ALTER TABLE metacritic ADD game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE metacritic ADD CONSTRAINT FK_AC9CAD34E48FD905 FOREIGN KEY (game_id) REFERENCES `game` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC9CAD34E48FD905 ON metacritic (game_id)');
        $this->addSql('ALTER TABLE pc_requirement ADD game_id INT DEFAULT NULL, CHANGE minimum minimum VARCHAR(1000) NOT NULL, CHANGE recommended recommended VARCHAR(1000) NOT NULL');
        $this->addSql('ALTER TABLE pc_requirement ADD CONSTRAINT FK_76BA5DD3E48FD905 FOREIGN KEY (game_id) REFERENCES `game` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_76BA5DD3E48FD905 ON pc_requirement (game_id)');
        $this->addSql('ALTER TABLE platform ADD game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE platform ADD CONSTRAINT FK_3952D0CBE48FD905 FOREIGN KEY (game_id) REFERENCES `game` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3952D0CBE48FD905 ON platform (game_id)');
        $this->addSql('ALTER TABLE release_date ADD game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE release_date ADD CONSTRAINT FK_E769876DE48FD905 FOREIGN KEY (game_id) REFERENCES `game` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E769876DE48FD905 ON release_date (game_id)');
        $this->addSql('ALTER TABLE screenshot ADD game_id INT NOT NULL');
        $this->addSql('ALTER TABLE screenshot ADD CONSTRAINT FK_58991E41E48FD905 FOREIGN KEY (game_id) REFERENCES `game` (id)');
        $this->addSql('CREATE INDEX IDX_58991E41E48FD905 ON screenshot (game_id)');
        $this->addSql('ALTER TABLE steam_review CHANGE id id INT NOT NULL, CHANGE recommended recommended VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genres (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE genre DROP FOREIGN KEY FK_835033F8E48FD905');
        $this->addSql('DROP TABLE genre');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1E48FD905');
        $this->addSql('DROP INDEX IDX_64C19C1E48FD905 ON category');
        $this->addSql('ALTER TABLE category DROP game_id');
        $this->addSql('ALTER TABLE metacritic DROP FOREIGN KEY FK_AC9CAD34E48FD905');
        $this->addSql('DROP INDEX UNIQ_AC9CAD34E48FD905 ON metacritic');
        $this->addSql('ALTER TABLE metacritic DROP game_id');
        $this->addSql('ALTER TABLE pc_requirement DROP FOREIGN KEY FK_76BA5DD3E48FD905');
        $this->addSql('DROP INDEX UNIQ_76BA5DD3E48FD905 ON pc_requirement');
        $this->addSql('ALTER TABLE pc_requirement DROP game_id, CHANGE minimum minimum VARCHAR(1000) DEFAULT NULL, CHANGE recommended recommended VARCHAR(1000) DEFAULT NULL');
        $this->addSql('ALTER TABLE platform DROP FOREIGN KEY FK_3952D0CBE48FD905');
        $this->addSql('DROP INDEX UNIQ_3952D0CBE48FD905 ON platform');
        $this->addSql('ALTER TABLE platform DROP game_id');
        $this->addSql('ALTER TABLE release_date DROP FOREIGN KEY FK_E769876DE48FD905');
        $this->addSql('DROP INDEX UNIQ_E769876DE48FD905 ON release_date');
        $this->addSql('ALTER TABLE release_date DROP game_id');
        $this->addSql('ALTER TABLE screenshot DROP FOREIGN KEY FK_58991E41E48FD905');
        $this->addSql('DROP INDEX IDX_58991E41E48FD905 ON screenshot');
        $this->addSql('ALTER TABLE screenshot DROP game_id');
        $this->addSql('ALTER TABLE steam_review CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE recommended recommended TINYINT(1) NOT NULL');
    }
}
