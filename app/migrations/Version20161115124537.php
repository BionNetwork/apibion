<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161115124537 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE argument_filter DROP CONSTRAINT fk_e3d0765172dcfbf6');
        $this->addSql('DROP SEQUENCE filter_control_type_id_seq CASCADE');
        $this->addSql('CREATE TABLE filter_types (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70817ACF5E237E06 ON filter_types (name)');
        $this->addSql('DROP TABLE filter_control_type');
        $this->addSql('ALTER TABLE argument_filter ADD CONSTRAINT FK_E3D0765172DCFBF6 FOREIGN KEY (filter_type_id) REFERENCES filter_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE argument_filter DROP CONSTRAINT FK_E3D0765172DCFBF6');
        $this->addSql('CREATE SEQUENCE filter_control_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE filter_control_type (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_8f9de90b5e237e06 ON filter_control_type (name)');
        $this->addSql('DROP TABLE filter_types');
        $this->addSql('ALTER TABLE argument_filter ADD CONSTRAINT fk_e3d0765172dcfbf6 FOREIGN KEY (filter_type_id) REFERENCES filter_control_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
