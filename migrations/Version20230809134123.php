<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230809134123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player ADD sport_id INT DEFAULT NULL, ADD league_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A6558AFC4DE FOREIGN KEY (league_id) REFERENCES league (id)');
        $this->addSql('CREATE INDEX IDX_98197A65AC78BCF8 ON player (sport_id)');
        $this->addSql('CREATE INDEX IDX_98197A6558AFC4DE ON player (league_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65AC78BCF8');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A6558AFC4DE');
        $this->addSql('DROP INDEX IDX_98197A65AC78BCF8 ON player');
        $this->addSql('DROP INDEX IDX_98197A6558AFC4DE ON player');
        $this->addSql('ALTER TABLE player DROP sport_id, DROP league_id');
    }
}
