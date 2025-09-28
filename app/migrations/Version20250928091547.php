<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250928091547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create task table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task_activity (id UUID NOT NULL, player_id UUID NOT NULL, date_start TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_end TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_ECB4E31699E6F5DF ON task_activity (player_id)');
        $this->addSql('COMMENT ON COLUMN task_activity.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task_activity.player_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task_activity.date_start IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN task_activity.date_end IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE task_activity ADD CONSTRAINT FK_ECB4E31699E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE task_activity DROP CONSTRAINT FK_ECB4E31699E6F5DF');
        $this->addSql('DROP TABLE task_activity');
    }
}
