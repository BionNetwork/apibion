<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161115124427 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE argument_filter DROP CONSTRAINT fk_e3d076516b168d02');
        $this->addSql('DROP INDEX idx_e3d076516b168d02');
        $this->addSql('ALTER TABLE argument_filter ALTER card_id SET NOT NULL');
        $this->addSql('ALTER TABLE argument_filter RENAME COLUMN filter_control_type_id TO filter_type_id');
        $this->addSql('ALTER TABLE argument_filter ADD CONSTRAINT FK_E3D0765172DCFBF6 FOREIGN KEY (filter_type_id) REFERENCES filter_control_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E3D0765172DCFBF6 ON argument_filter (filter_type_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE argument_filter DROP CONSTRAINT FK_E3D0765172DCFBF6');
        $this->addSql('DROP INDEX IDX_E3D0765172DCFBF6');
        $this->addSql('ALTER TABLE argument_filter ALTER card_id DROP NOT NULL');
        $this->addSql('ALTER TABLE argument_filter RENAME COLUMN filter_type_id TO filter_control_type_id');
        $this->addSql('ALTER TABLE argument_filter ADD CONSTRAINT fk_e3d076516b168d02 FOREIGN KEY (filter_control_type_id) REFERENCES filter_control_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_e3d076516b168d02 ON argument_filter (filter_control_type_id)');
    }
}
