<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251001065125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'CAR-16: Add looser to SessionScoring and move scores from Task to SessionScoring';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE session_scoring ADD looser_id UUID NOT NULL');
        $this->addSql('ALTER TABLE session_scoring ADD winner_score INT NOT NULL');
        $this->addSql('ALTER TABLE session_scoring ADD looser_score INT NOT NULL');
        $this->addSql('COMMENT ON COLUMN session_scoring.looser_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE session_scoring ADD CONSTRAINT FK_69AE2090AC391B62 FOREIGN KEY (looser_id) REFERENCES player (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_69AE2090AC391B62 ON session_scoring (looser_id)');
        $this->addSql('ALTER TABLE task_activity ADD scoring_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN task_activity.scoring_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE task_activity ADD CONSTRAINT FK_ECB4E316DF2EDCBF FOREIGN KEY (scoring_id) REFERENCES session_scoring (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_ECB4E316DF2EDCBF ON task_activity (scoring_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE task_activity DROP CONSTRAINT FK_ECB4E316DF2EDCBF');
        $this->addSql('DROP INDEX IDX_ECB4E316DF2EDCBF');
        $this->addSql('ALTER TABLE task_activity DROP scoring_id');
        $this->addSql('ALTER TABLE session_scoring DROP CONSTRAINT FK_69AE2090AC391B62');
        $this->addSql('DROP INDEX IDX_69AE2090AC391B62');
        $this->addSql('ALTER TABLE session_scoring DROP looser_id');
        $this->addSql('ALTER TABLE session_scoring DROP winner_score');
        $this->addSql('ALTER TABLE session_scoring DROP looser_score');
    }
}
