<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20161011110238 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE card ADD image_file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE card DROP image');
        $this->addSql('ALTER TABLE card DROP carousel');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D36DB2EB0 FOREIGN KEY (image_file_id) REFERENCES file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_161498D36DB2EB0 ON card (image_file_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE card DROP CONSTRAINT FK_161498D36DB2EB0');
        $this->addSql('DROP INDEX IDX_161498D36DB2EB0');
        $this->addSql('ALTER TABLE card ADD image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE card ADD carousel VARCHAR(4096) DEFAULT NULL');
        $this->addSql('ALTER TABLE card DROP image_file_id');
    }
}
