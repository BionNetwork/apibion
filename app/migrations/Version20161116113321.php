<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161116113321 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE card_representation_id_seq CASCADE');
        $this->addSql('CREATE TABLE card_charts (id SERIAL NOT NULL, activation_id INT DEFAULT NULL, card_id INT DEFAULT NULL, chart_id INT DEFAULT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B26F0383116B3934 ON card_charts (activation_id)');
        $this->addSql('CREATE INDEX IDX_B26F03834ACC9A20 ON card_charts (card_id)');
        $this->addSql('CREATE INDEX IDX_B26F0383BEF83E0A ON card_charts (chart_id)');
        $this->addSql('CREATE INDEX card_chart_create_on_idx ON card_charts (created_on)');
        $this->addSql('ALTER TABLE card_charts ADD CONSTRAINT FK_B26F0383116B3934 FOREIGN KEY (activation_id) REFERENCES activation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE card_charts ADD CONSTRAINT FK_B26F03834ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE card_charts ADD CONSTRAINT FK_B26F0383BEF83E0A FOREIGN KEY (chart_id) REFERENCES charts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE card_representation');
        $this->addSql('CREATE INDEX representation_create_on_idx ON charts (created_on)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE card_representation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE card_representation (id SERIAL NOT NULL, activation_id INT DEFAULT NULL, card_id INT DEFAULT NULL, chart_id INT DEFAULT NULL, created_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_65cc2309116b3934 ON card_representation (activation_id)');
        $this->addSql('CREATE INDEX card_representation_create_on_idx ON card_representation (created_on)');
        $this->addSql('CREATE INDEX idx_65cc2309bef83e0a ON card_representation (chart_id)');
        $this->addSql('CREATE INDEX idx_65cc23094acc9a20 ON card_representation (card_id)');
        $this->addSql('ALTER TABLE card_representation ADD CONSTRAINT fk_65cc2309116b3934 FOREIGN KEY (activation_id) REFERENCES activation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE card_representation ADD CONSTRAINT fk_65cc23094acc9a20 FOREIGN KEY (card_id) REFERENCES card (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE card_representation ADD CONSTRAINT fk_65cc2309bef83e0a FOREIGN KEY (chart_id) REFERENCES charts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE card_charts');
        $this->addSql('DROP INDEX representation_create_on_idx');
    }
}
