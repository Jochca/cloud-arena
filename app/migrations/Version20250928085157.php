<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250928085157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create session table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE session (id UUID NOT NULL, player_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D044D5D499E6F5DF ON session (player_id)');
        $this->addSql('COMMENT ON COLUMN session.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN session.player_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D499E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE session DROP CONSTRAINT FK_D044D5D499E6F5DF');
        $this->addSql('DROP TABLE session');
    }
}
