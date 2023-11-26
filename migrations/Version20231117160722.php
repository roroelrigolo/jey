<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231117160722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sport ADD banner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sport ADD CONSTRAINT FK_1A85EFD2684EC833 FOREIGN KEY (banner_id) REFERENCES image (id)');
        $this->addSql('CREATE INDEX IDX_1A85EFD2684EC833 ON sport (banner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sport DROP FOREIGN KEY FK_1A85EFD2684EC833');
        $this->addSql('DROP INDEX IDX_1A85EFD2684EC833 ON sport');
        $this->addSql('ALTER TABLE sport DROP banner_id');
    }
}
