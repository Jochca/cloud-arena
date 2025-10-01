<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251001051331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'CAR-15: Add relation between TaskActivity and Task';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_activity ADD task_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN task_activity.task_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE task_activity ADD CONSTRAINT FK_ECB4E3168DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_ECB4E3168DB60186 ON task_activity (task_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE task_activity DROP CONSTRAINT FK_ECB4E3168DB60186');
        $this->addSql('DROP INDEX IDX_ECB4E3168DB60186');
        $this->addSql('ALTER TABLE task_activity DROP task_id');
    }
}
