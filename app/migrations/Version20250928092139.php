<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250928092139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create session_scoring table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE session_scoring (id UUID NOT NULL, winner_id UUID NOT NULL, session_id UUID NOT NULL, date_start TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_end TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_69AE20905DFCD4B8 ON session_scoring (winner_id)');
        $this->addSql('CREATE INDEX IDX_69AE2090613FECDF ON session_scoring (session_id)');
        $this->addSql('COMMENT ON COLUMN session_scoring.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN session_scoring.winner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN session_scoring.session_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN session_scoring.date_start IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN session_scoring.date_end IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE session_scoring ADD CONSTRAINT FK_69AE20905DFCD4B8 FOREIGN KEY (winner_id) REFERENCES player (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE session_scoring ADD CONSTRAINT FK_69AE2090613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE session_scoring DROP CONSTRAINT FK_69AE20905DFCD4B8');
        $this->addSql('ALTER TABLE session_scoring DROP CONSTRAINT FK_69AE2090613FECDF');
        $this->addSql('DROP TABLE session_scoring');
    }
}
