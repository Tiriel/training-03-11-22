<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220801131609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add book::comment relationship';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, posted_at, message, author FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, book_id INTEGER NOT NULL, posted_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , message CLOB NOT NULL, author VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_9474526C16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO comment (id, posted_at, message, author) SELECT id, posted_at, message, author FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526C16A2B381 ON comment (book_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_9474526C16A2B381');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, posted_at, message, author FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, posted_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , message CLOB NOT NULL, author VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO comment (id, posted_at, message, author) SELECT id, posted_at, message, author FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
    }
}
