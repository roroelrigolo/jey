<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230810092137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD league_id INT DEFAULT NULL, ADD player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD58AFC4DE FOREIGN KEY (league_id) REFERENCES league (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD58AFC4DE ON product (league_id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD99E6F5DF ON product (player_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD58AFC4DE');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD99E6F5DF');
        $this->addSql('DROP INDEX IDX_D34A04AD58AFC4DE ON product');
        $this->addSql('DROP INDEX IDX_D34A04AD99E6F5DF ON product');
        $this->addSql('ALTER TABLE product DROP league_id, DROP player_id');
    }
}
