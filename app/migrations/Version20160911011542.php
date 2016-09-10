<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160911011542 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE argument_bond DROP CONSTRAINT FK_DAB42FF13DD48F21');
        $this->addSql('ALTER TABLE argument_bond DROP CONSTRAINT FK_DAB42FF189329D25');
        $this->addSql('ALTER TABLE argument_bond DROP CONSTRAINT FK_DAB42FF1116B3934');
        $this->addSql('ALTER TABLE argument_bond ADD CONSTRAINT FK_DAB42FF13DD48F21 FOREIGN KEY (argument_id) REFERENCES argument (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE argument_bond ADD CONSTRAINT FK_DAB42FF189329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE argument_bond ADD CONSTRAINT FK_DAB42FF1116B3934 FOREIGN KEY (activation_id) REFERENCES activation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE argument_bond DROP CONSTRAINT fk_dab42ff13dd48f21');
        $this->addSql('ALTER TABLE argument_bond DROP CONSTRAINT fk_dab42ff189329d25');
        $this->addSql('ALTER TABLE argument_bond DROP CONSTRAINT fk_dab42ff1116b3934');
        $this->addSql('ALTER TABLE argument_bond ADD CONSTRAINT fk_dab42ff13dd48f21 FOREIGN KEY (argument_id) REFERENCES argument (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE argument_bond ADD CONSTRAINT fk_dab42ff189329d25 FOREIGN KEY (resource_id) REFERENCES resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE argument_bond ADD CONSTRAINT fk_dab42ff1116b3934 FOREIGN KEY (activation_id) REFERENCES activation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
