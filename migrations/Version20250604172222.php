<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250604172222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE status ADD need_score INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD confidence INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD effort INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD impact DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE task ADD reach DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE status DROP need_score');
        $this->addSql('ALTER TABLE task DROP confidence');
        $this->addSql('ALTER TABLE task DROP effort');
        $this->addSql('ALTER TABLE task DROP impact');
        $this->addSql('ALTER TABLE task DROP reach');
    }
}
