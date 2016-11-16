<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161116112538 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE card_representation DROP CONSTRAINT fk_65cc230946ce82f4');
        $this->addSql('DROP SEQUENCE representation_id_seq CASCADE');
        $this->addSql('CREATE TABLE charts (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO charts (name, code) SELECT name, code FROM representation');
        $this->addSql('DROP TABLE representation');
        $this->addSql('DROP INDEX idx_65cc230946ce82f4');
        $this->addSql('ALTER TABLE card_representation RENAME COLUMN representation_id TO chart_id');
        $this->addSql('ALTER TABLE card_representation ADD CONSTRAINT FK_65CC2309BEF83E0A FOREIGN KEY (chart_id) REFERENCES charts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_65CC2309BEF83E0A ON card_representation (chart_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE card_representation DROP CONSTRAINT FK_65CC2309BEF83E0A');
        $this->addSql('CREATE SEQUENCE representation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE representation (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO representation (name, code) SELECT name, code FROM charts');
        $this->addSql('DROP TABLE charts');
        $this->addSql('DROP INDEX IDX_65CC2309BEF83E0A');
        $this->addSql('ALTER TABLE card_representation RENAME COLUMN chart_id TO representation_id');
        $this->addSql('ALTER TABLE card_representation ADD CONSTRAINT fk_65cc230946ce82f4 FOREIGN KEY (representation_id) REFERENCES representation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_65cc230946ce82f4 ON card_representation (representation_id)');
    }
}
