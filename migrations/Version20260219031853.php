<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260219031853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_history DROP FOREIGN KEY `FK_7CDCC97093CB796C`');
        $this->addSql('ALTER TABLE file_history DROP FOREIGN KEY `FK_7CDCC970F675F31B`');
        $this->addSql('DROP TABLE file_history');
        $this->addSql('ALTER TABLE file ADD date DATETIME NOT NULL, ADD link VARCHAR(255) NOT NULL, ADD author_id INT NOT NULL, DROP type');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8C9F3610F675F31B ON file (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file_history (id INT AUTO_INCREMENT NOT NULL, link VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date DATETIME NOT NULL, comment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, file_id INT NOT NULL, author_id INT NOT NULL, INDEX IDX_7CDCC97093CB796C (file_id), INDEX IDX_7CDCC970F675F31B (author_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE file_history ADD CONSTRAINT `FK_7CDCC97093CB796C` FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE file_history ADD CONSTRAINT `FK_7CDCC970F675F31B` FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610F675F31B');
        $this->addSql('DROP INDEX IDX_8C9F3610F675F31B ON file');
        $this->addSql('ALTER TABLE file ADD type SMALLINT NOT NULL, DROP date, DROP link, DROP author_id');
    }
}
