<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161013110522 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE argument_filter_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE argument_filter (id INT NOT NULL, filter_control_type_id INT DEFAULT NULL, card_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E3D076516B168D02 ON argument_filter (filter_control_type_id)');
        $this->addSql('CREATE INDEX IDX_E3D076514ACC9A20 ON argument_filter (card_id)');
        $this->addSql('CREATE TABLE arguments_to_arguments_filters (argument_id INT NOT NULL, argument_filter_id INT NOT NULL, PRIMARY KEY(argument_id, argument_filter_id))');
        $this->addSql('CREATE INDEX IDX_5A3852A33DD48F21 ON arguments_to_arguments_filters (argument_id)');
        $this->addSql('CREATE INDEX IDX_5A3852A3F4898DF0 ON arguments_to_arguments_filters (argument_filter_id)');
        $this->addSql('ALTER TABLE argument_filter ADD CONSTRAINT FK_E3D076516B168D02 FOREIGN KEY (filter_control_type_id) REFERENCES filter_control_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE argument_filter ADD CONSTRAINT FK_E3D076514ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE arguments_to_arguments_filters ADD CONSTRAINT FK_5A3852A33DD48F21 FOREIGN KEY (argument_id) REFERENCES argument (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE arguments_to_arguments_filters ADD CONSTRAINT FK_5A3852A3F4898DF0 FOREIGN KEY (argument_filter_id) REFERENCES argument_filter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE activation_setting DROP CONSTRAINT FK_87A87A3D116B3934');
        $this->addSql('ALTER TABLE activation_setting ADD CONSTRAINT FK_87A87A3D116B3934 FOREIGN KEY (activation_id) REFERENCES activation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE argument DROP CONSTRAINT fk_d113b0a6b168d02');
        $this->addSql('DROP INDEX idx_d113b0a6b168d02');
        $this->addSql('ALTER TABLE argument DROP filter_control_type_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE arguments_to_arguments_filters DROP CONSTRAINT FK_5A3852A3F4898DF0');
        $this->addSql('DROP SEQUENCE argument_filter_id_seq CASCADE');
        $this->addSql('DROP TABLE argument_filter');
        $this->addSql('DROP TABLE arguments_to_arguments_filters');
        $this->addSql('ALTER TABLE argument ADD filter_control_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE argument ADD CONSTRAINT fk_d113b0a6b168d02 FOREIGN KEY (filter_control_type_id) REFERENCES filter_control_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d113b0a6b168d02 ON argument (filter_control_type_id)');
        $this->addSql('ALTER TABLE activation_setting DROP CONSTRAINT fk_87a87a3d116b3934');
        $this->addSql('ALTER TABLE activation_setting ADD CONSTRAINT fk_87a87a3d116b3934 FOREIGN KEY (activation_id) REFERENCES activation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
