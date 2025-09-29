<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929173505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'CAR-4: Change relation between Player and Session: make Session the owning side';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player ADD session_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN player.session_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_98197A65613FECDF ON player (session_id)');
        $this->addSql('ALTER TABLE session DROP CONSTRAINT fk_d044d5d499e6f5df');
        $this->addSql('DROP INDEX idx_d044d5d499e6f5df');
        $this->addSql('ALTER TABLE session DROP player_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE player DROP CONSTRAINT FK_98197A65613FECDF');
        $this->addSql('DROP INDEX IDX_98197A65613FECDF');
        $this->addSql('ALTER TABLE player DROP session_id');
        $this->addSql('ALTER TABLE session ADD player_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN session.player_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT fk_d044d5d499e6f5df FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d044d5d499e6f5df ON session (player_id)');
    }
}
