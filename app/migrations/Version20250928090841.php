<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250928090841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create task table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task (id UUID NOT NULL, session_id UUID NOT NULL, player_id UUID DEFAULT NULL, type VARCHAR(255) NOT NULL, value INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_527EDB25613FECDF ON task (session_id)');
        $this->addSql('CREATE INDEX IDX_527EDB2599E6F5DF ON task (player_id)');
        $this->addSql('COMMENT ON COLUMN task.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task.session_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN task.player_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25613FECDF');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB2599E6F5DF');
        $this->addSql('DROP TABLE task');
    }
}
