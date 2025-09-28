<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250928085458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create session_player_key table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE session_player_key (id UUID NOT NULL, player_id UUID NOT NULL, session_id UUID NOT NULL, key INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1727E1D199E6F5DF ON session_player_key (player_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1727E1D1613FECDF ON session_player_key (session_id)');
        $this->addSql('COMMENT ON COLUMN session_player_key.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN session_player_key.player_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN session_player_key.session_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE session_player_key ADD CONSTRAINT FK_1727E1D199E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE session_player_key ADD CONSTRAINT FK_1727E1D1613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE session_player_key DROP CONSTRAINT FK_1727E1D199E6F5DF');
        $this->addSql('ALTER TABLE session_player_key DROP CONSTRAINT FK_1727E1D1613FECDF');
        $this->addSql('DROP TABLE session_player_key');
    }
}
