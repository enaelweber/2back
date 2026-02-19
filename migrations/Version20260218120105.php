<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260218120105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) NOT NULL, type SMALLINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE file_history (id INT AUTO_INCREMENT NOT NULL, link VARCHAR(255) NOT NULL, date DATETIME NOT NULL, comment VARCHAR(255) DEFAULT NULL, file_id_id INT NOT NULL, author_id_id INT NOT NULL, INDEX IDX_7CDCC970D5C72E60 (file_id_id), INDEX IDX_7CDCC97069CCBE9A (author_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE revision_history (id INT AUTO_INCREMENT NOT NULL, wikitext LONGTEXT DEFAULT NULL, date DATETIME NOT NULL, changes INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, page_id_id INT NOT NULL, author_id_id INT NOT NULL, INDEX IDX_A4DFBE2F19C56181 (page_id_id), INDEX IDX_A4DFBE2F69CCBE9A (author_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE file_history ADD CONSTRAINT FK_7CDCC970D5C72E60 FOREIGN KEY (file_id_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE file_history ADD CONSTRAINT FK_7CDCC97069CCBE9A FOREIGN KEY (author_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE revision_history ADD CONSTRAINT FK_A4DFBE2F19C56181 FOREIGN KEY (page_id_id) REFERENCES page (id)');
        $this->addSql('ALTER TABLE revision_history ADD CONSTRAINT FK_A4DFBE2F69CCBE9A FOREIGN KEY (author_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_history DROP FOREIGN KEY FK_7CDCC970D5C72E60');
        $this->addSql('ALTER TABLE file_history DROP FOREIGN KEY FK_7CDCC97069CCBE9A');
        $this->addSql('ALTER TABLE revision_history DROP FOREIGN KEY FK_A4DFBE2F19C56181');
        $this->addSql('ALTER TABLE revision_history DROP FOREIGN KEY FK_A4DFBE2F69CCBE9A');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE file_history');
        $this->addSql('DROP TABLE revision_history');
    }
}
