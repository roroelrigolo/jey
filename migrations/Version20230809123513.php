<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230809123513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE league (id INT AUTO_INCREMENT NOT NULL, sport_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_3EB4C318AC78BCF8 (sport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE league ADD CONSTRAINT FK_3EB4C318AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE league DROP FOREIGN KEY FK_3EB4C318AC78BCF8');
        $this->addSql('DROP TABLE league');
    }
}
