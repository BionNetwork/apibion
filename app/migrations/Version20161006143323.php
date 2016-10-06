<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20161006143323 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE filter_control_type (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8F9DE90B5E237E06 ON filter_control_type (name)');
        $this->addSql('ALTER TABLE activation DROP CONSTRAINT FK_1C6860774ACC9A20');
        $this->addSql('ALTER TABLE activation DROP CONSTRAINT FK_1C686077CDEDF351');
        $this->addSql('ALTER TABLE activation ADD CONSTRAINT FK_1C6860774ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE activation ADD CONSTRAINT FK_1C686077CDEDF351 FOREIGN KEY (activation_status_id) REFERENCES activation_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE argument ADD filter_control_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE argument ADD filtered BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('ALTER TABLE argument ADD CONSTRAINT FK_D113B0A6B168D02 FOREIGN KEY (filter_control_type_id) REFERENCES filter_control_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D113B0A6B168D02 ON argument (filter_control_type_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE argument DROP CONSTRAINT FK_D113B0A6B168D02');
        $this->addSql('DROP TABLE filter_control_type');
        $this->addSql('DROP INDEX IDX_D113B0A6B168D02');
        $this->addSql('ALTER TABLE argument DROP filter_control_type_id');
        $this->addSql('ALTER TABLE argument DROP filtered');
        $this->addSql('ALTER TABLE activation DROP CONSTRAINT fk_1c6860774acc9a20');
        $this->addSql('ALTER TABLE activation DROP CONSTRAINT fk_1c686077cdedf351');
        $this->addSql('ALTER TABLE activation ADD CONSTRAINT fk_1c6860774acc9a20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE activation ADD CONSTRAINT fk_1c686077cdedf351 FOREIGN KEY (activation_status_id) REFERENCES activation_status (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
