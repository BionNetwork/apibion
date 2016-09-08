<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160908200231 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE argument_bond (id SERIAL NOT NULL, argument_id INT DEFAULT NULL, resource_id INT DEFAULT NULL, activation_id INT DEFAULT NULL, table_name VARCHAR(255) NOT NULL, column_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DAB42FF13DD48F21 ON argument_bond (argument_id)');
        $this->addSql('CREATE INDEX IDX_DAB42FF189329D25 ON argument_bond (resource_id)');
        $this->addSql('CREATE INDEX IDX_DAB42FF1116B3934 ON argument_bond (activation_id)');
        $this->addSql('ALTER TABLE argument_bond ADD CONSTRAINT FK_DAB42FF13DD48F21 FOREIGN KEY (argument_id) REFERENCES argument (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE argument_bond ADD CONSTRAINT FK_DAB42FF189329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE argument_bond ADD CONSTRAINT FK_DAB42FF1116B3934 FOREIGN KEY (activation_id) REFERENCES activation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE argument_bond');
    }
}
