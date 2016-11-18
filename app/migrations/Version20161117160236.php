<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161117160236 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE card DROP CONSTRAINT fk_161498d36db2eb0');
        $this->addSql('DROP INDEX idx_161498d36db2eb0');
        $this->addSql('ALTER TABLE card RENAME COLUMN image_file_id TO image_id');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D33DA5256D FOREIGN KEY (image_id) REFERENCES file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_161498D33DA5256D ON card (image_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE card DROP CONSTRAINT FK_161498D33DA5256D');
        $this->addSql('DROP INDEX IDX_161498D33DA5256D');
        $this->addSql('ALTER TABLE card RENAME COLUMN image_id TO image_file_id');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT fk_161498d36db2eb0 FOREIGN KEY (image_file_id) REFERENCES file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_161498d36db2eb0 ON card (image_file_id)');
    }
}
