<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221103094609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD COLUMN status VARCHAR(50) DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_9474526C16A2B381');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, book_id, posted_at, message, author FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, book_id INTEGER NOT NULL, posted_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , message CLOB NOT NULL, author VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_9474526C16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO comment (id, book_id, posted_at, message, author) SELECT id, book_id, posted_at, message, author FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526C16A2B381 ON comment (book_id)');
        $this->addSql('DROP INDEX IDX_1D5EF26F55B127A4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__movie AS SELECT id, added_by_id, title, poster, country, released_at, price, rated, omdb_id FROM movie');
        $this->addSql('DROP TABLE movie');
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, added_by_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, poster VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, released_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , price NUMERIC(4, 2) NOT NULL, rated VARCHAR(10) NOT NULL, omdb_id VARCHAR(50) NOT NULL, CONSTRAINT FK_1D5EF26F55B127A4 FOREIGN KEY (added_by_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO movie (id, added_by_id, title, poster, country, released_at, price, rated, omdb_id) SELECT id, added_by_id, title, poster, country, released_at, price, rated, omdb_id FROM __temp__movie');
        $this->addSql('DROP TABLE __temp__movie');
        $this->addSql('CREATE INDEX IDX_1D5EF26F55B127A4 ON movie (added_by_id)');
        $this->addSql('DROP INDEX IDX_FD1229648F93B6FC');
        $this->addSql('DROP INDEX IDX_FD1229644296D31F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__movie_genre AS SELECT movie_id, genre_id FROM movie_genre');
        $this->addSql('DROP TABLE movie_genre');
        $this->addSql('CREATE TABLE movie_genre (movie_id INTEGER NOT NULL, genre_id INTEGER NOT NULL, PRIMARY KEY(movie_id, genre_id), CONSTRAINT FK_FD1229648F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FD1229644296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO movie_genre (movie_id, genre_id) SELECT movie_id, genre_id FROM __temp__movie_genre');
        $this->addSql('DROP TABLE __temp__movie_genre');
        $this->addSql('CREATE INDEX IDX_FD1229648F93B6FC ON movie_genre (movie_id)');
        $this->addSql('CREATE INDEX IDX_FD1229644296D31F ON movie_genre (genre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__book AS SELECT id, title, isbn, released_at, author, price FROM book');
        $this->addSql('DROP TABLE book');
        $this->addSql('CREATE TABLE book (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, isbn VARCHAR(20) NOT NULL, released_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , author VARCHAR(255) NOT NULL, price NUMERIC(4, 2) NOT NULL)');
        $this->addSql('INSERT INTO book (id, title, isbn, released_at, author, price) SELECT id, title, isbn, released_at, author, price FROM __temp__book');
        $this->addSql('DROP TABLE __temp__book');
        $this->addSql('DROP INDEX IDX_9474526C16A2B381');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, book_id, posted_at, message, author FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, book_id INTEGER NOT NULL, posted_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , message CLOB NOT NULL, author VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO comment (id, book_id, posted_at, message, author) SELECT id, book_id, posted_at, message, author FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526C16A2B381 ON comment (book_id)');
        $this->addSql('DROP INDEX IDX_1D5EF26F55B127A4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__movie AS SELECT id, added_by_id, title, poster, country, released_at, price, rated, omdb_id FROM movie');
        $this->addSql('DROP TABLE movie');
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, added_by_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, poster VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, released_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , price NUMERIC(4, 2) NOT NULL, rated VARCHAR(10) NOT NULL, omdb_id VARCHAR(50) NOT NULL)');
        $this->addSql('INSERT INTO movie (id, added_by_id, title, poster, country, released_at, price, rated, omdb_id) SELECT id, added_by_id, title, poster, country, released_at, price, rated, omdb_id FROM __temp__movie');
        $this->addSql('DROP TABLE __temp__movie');
        $this->addSql('CREATE INDEX IDX_1D5EF26F55B127A4 ON movie (added_by_id)');
        $this->addSql('DROP INDEX IDX_FD1229648F93B6FC');
        $this->addSql('DROP INDEX IDX_FD1229644296D31F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__movie_genre AS SELECT movie_id, genre_id FROM movie_genre');
        $this->addSql('DROP TABLE movie_genre');
        $this->addSql('CREATE TABLE movie_genre (movie_id INTEGER NOT NULL, genre_id INTEGER NOT NULL, PRIMARY KEY(movie_id, genre_id))');
        $this->addSql('INSERT INTO movie_genre (movie_id, genre_id) SELECT movie_id, genre_id FROM __temp__movie_genre');
        $this->addSql('DROP TABLE __temp__movie_genre');
        $this->addSql('CREATE INDEX IDX_FD1229648F93B6FC ON movie_genre (movie_id)');
        $this->addSql('CREATE INDEX IDX_FD1229644296D31F ON movie_genre (genre_id)');
    }
}
