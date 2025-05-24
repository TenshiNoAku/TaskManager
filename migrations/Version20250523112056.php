<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250523112056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bug_report (id SERIAL NOT NULL, task_id INT DEFAULT NULL, is_duplicate_of_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, text TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F6F2DC7A8DB60186 ON bug_report (task_id)');
        $this->addSql('CREATE INDEX IDX_F6F2DC7AF25E15BE ON bug_report (is_duplicate_of_id)');
        $this->addSql('ALTER TABLE bug_report ADD CONSTRAINT FK_F6F2DC7A8DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bug_report ADD CONSTRAINT FK_F6F2DC7AF25E15BE FOREIGN KEY (is_duplicate_of_id) REFERENCES bug_report (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE bug_report DROP CONSTRAINT FK_F6F2DC7A8DB60186');
        $this->addSql('ALTER TABLE bug_report DROP CONSTRAINT FK_F6F2DC7AF25E15BE');
        $this->addSql('DROP TABLE bug_report');
    }
}
