<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260218121502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_history DROP FOREIGN KEY `FK_7CDCC97069CCBE9A`');
        $this->addSql('ALTER TABLE file_history DROP FOREIGN KEY `FK_7CDCC970D5C72E60`');
        $this->addSql('DROP INDEX IDX_7CDCC970D5C72E60 ON file_history');
        $this->addSql('DROP INDEX IDX_7CDCC97069CCBE9A ON file_history');
        $this->addSql('ALTER TABLE file_history ADD file_id INT NOT NULL, ADD author_id INT NOT NULL, DROP file_id_id, DROP author_id_id');
        $this->addSql('ALTER TABLE file_history ADD CONSTRAINT FK_7CDCC97093CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE file_history ADD CONSTRAINT FK_7CDCC970F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_7CDCC97093CB796C ON file_history (file_id)');
        $this->addSql('CREATE INDEX IDX_7CDCC970F675F31B ON file_history (author_id)');
        $this->addSql('ALTER TABLE revision_history DROP FOREIGN KEY `FK_A4DFBE2F69CCBE9A`');
        $this->addSql('DROP INDEX IDX_A4DFBE2F69CCBE9A ON revision_history');
        $this->addSql('ALTER TABLE revision_history CHANGE author_id_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE revision_history ADD CONSTRAINT FK_A4DFBE2FF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A4DFBE2FF675F31B ON revision_history (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_history DROP FOREIGN KEY FK_7CDCC97093CB796C');
        $this->addSql('ALTER TABLE file_history DROP FOREIGN KEY FK_7CDCC970F675F31B');
        $this->addSql('DROP INDEX IDX_7CDCC97093CB796C ON file_history');
        $this->addSql('DROP INDEX IDX_7CDCC970F675F31B ON file_history');
        $this->addSql('ALTER TABLE file_history ADD file_id_id INT NOT NULL, ADD author_id_id INT NOT NULL, DROP file_id, DROP author_id');
        $this->addSql('ALTER TABLE file_history ADD CONSTRAINT `FK_7CDCC97069CCBE9A` FOREIGN KEY (author_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE file_history ADD CONSTRAINT `FK_7CDCC970D5C72E60` FOREIGN KEY (file_id_id) REFERENCES file (id)');
        $this->addSql('CREATE INDEX IDX_7CDCC970D5C72E60 ON file_history (file_id_id)');
        $this->addSql('CREATE INDEX IDX_7CDCC97069CCBE9A ON file_history (author_id_id)');
        $this->addSql('ALTER TABLE revision_history DROP FOREIGN KEY FK_A4DFBE2FF675F31B');
        $this->addSql('DROP INDEX IDX_A4DFBE2FF675F31B ON revision_history');
        $this->addSql('ALTER TABLE revision_history CHANGE author_id author_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE revision_history ADD CONSTRAINT `FK_A4DFBE2F69CCBE9A` FOREIGN KEY (author_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A4DFBE2F69CCBE9A ON revision_history (author_id_id)');
    }
}
