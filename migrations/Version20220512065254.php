<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220512065254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video ADD thumbnail VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7CC7DA2C2B36786B ON video (title)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7CC7DA2CB548B0F ON video (path)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_7CC7DA2C2B36786B ON video');
        $this->addSql('DROP INDEX UNIQ_7CC7DA2CB548B0F ON video');
        $this->addSql('ALTER TABLE video DROP thumbnail');
    }
}
