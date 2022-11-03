<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221103100455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_1D5EF26F55B127A4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__movie AS SELECT id, added_by_id, title, poster, country, released_at, price, rated, omdb_id FROM movie');
        $this->addSql('DROP TABLE movie');
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, added_by_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, poster VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, released_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , price NUMERIC(4, 2) NOT NULL, rated VARCHAR(10) NOT NULL, omdb_id VARCHAR(50) NOT NULL, status VARCHAR(50) DEFAULT NULL, CONSTRAINT FK_1D5EF26F55B127A4 FOREIGN KEY (added_by_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO movie (id, added_by_id, title, poster, country, released_at, price, rated, omdb_id) SELECT id, added_by_id, title, poster, country, released_at, price, rated, omdb_id FROM __temp__movie');
        $this->addSql('DROP TABLE __temp__movie');
        $this->addSql('CREATE INDEX IDX_1D5EF26F55B127A4 ON movie (added_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_1D5EF26F55B127A4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__movie AS SELECT id, added_by_id, title, poster, country, released_at, price, rated, omdb_id FROM movie');
        $this->addSql('DROP TABLE movie');
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, added_by_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, poster VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, released_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , price NUMERIC(4, 2) NOT NULL, rated VARCHAR(10) NOT NULL, omdb_id VARCHAR(50) NOT NULL)');
        $this->addSql('INSERT INTO movie (id, added_by_id, title, poster, country, released_at, price, rated, omdb_id) SELECT id, added_by_id, title, poster, country, released_at, price, rated, omdb_id FROM __temp__movie');
        $this->addSql('DROP TABLE __temp__movie');
        $this->addSql('CREATE INDEX IDX_1D5EF26F55B127A4 ON movie (added_by_id)');
    }
}
