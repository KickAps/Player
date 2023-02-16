<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230216093502 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_7CC7DA2CB548B0F ON video');
        $this->addSql('ALTER TABLE video ADD onedrive_authkey VARCHAR(255) NOT NULL, CHANGE path onedrive_id VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video ADD path VARCHAR(255) NOT NULL, DROP onedrive_id, DROP onedrive_authkey');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7CC7DA2CB548B0F ON video (path)');
    }
}
