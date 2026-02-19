<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260218121302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE revision_history DROP FOREIGN KEY `FK_A4DFBE2F401ADD27`');
        $this->addSql('DROP INDEX IDX_A4DFBE2F401ADD27 ON revision_history');
        $this->addSql('ALTER TABLE revision_history CHANGE pages_id page_id INT NOT NULL');
        $this->addSql('ALTER TABLE revision_history ADD CONSTRAINT FK_A4DFBE2FC4663E4 FOREIGN KEY (page_id) REFERENCES page (id)');
        $this->addSql('CREATE INDEX IDX_A4DFBE2FC4663E4 ON revision_history (page_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE revision_history DROP FOREIGN KEY FK_A4DFBE2FC4663E4');
        $this->addSql('DROP INDEX IDX_A4DFBE2FC4663E4 ON revision_history');
        $this->addSql('ALTER TABLE revision_history CHANGE page_id pages_id INT NOT NULL');
        $this->addSql('ALTER TABLE revision_history ADD CONSTRAINT `FK_A4DFBE2F401ADD27` FOREIGN KEY (pages_id) REFERENCES page (id)');
        $this->addSql('CREATE INDEX IDX_A4DFBE2F401ADD27 ON revision_history (pages_id)');
    }
}
