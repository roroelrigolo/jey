<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230809101538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD sport_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADAC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADAC78BCF8 ON product (sport_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADAC78BCF8');
        $this->addSql('DROP INDEX IDX_D34A04ADAC78BCF8 ON product');
        $this->addSql('ALTER TABLE product DROP sport_id');
    }
}
