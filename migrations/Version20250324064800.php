<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250324064800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE priority (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE project (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE status (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE task (id SERIAL NOT NULL, project_id INT NOT NULL, tracker_id INT NOT NULL, status_id INT NOT NULL, priority_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, from_date DATE NOT NULL, deadline DATE NOT NULL, time_cost TIME(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_527EDB25166D1F9C ON task (project_id)');
        $this->addSql('CREATE INDEX IDX_527EDB25FB5230B ON task (tracker_id)');
        $this->addSql('CREATE INDEX IDX_527EDB256BF700BD ON task (status_id)');
        $this->addSql('CREATE INDEX IDX_527EDB25497B19F9 ON task (priority_id)');
        $this->addSql('CREATE TABLE tracker (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25FB5230B FOREIGN KEY (tracker_id) REFERENCES tracker (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB256BF700BD FOREIGN KEY (status_id) REFERENCES status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25497B19F9 FOREIGN KEY (priority_id) REFERENCES priority (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25166D1F9C');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25FB5230B');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB256BF700BD');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25497B19F9');
        $this->addSql('DROP TABLE priority');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE tracker');
    }
}
