<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161117161911 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $sequenceId = $this->connection->fetchColumn("SELECT MAX(id) FROM card_images");
        $sequenceId = $sequenceId + 1;
        $this->addSql('DROP SEQUENCE card_carousel_image_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE card_images_id_seq INCREMENT BY 1 MINVALUE 1 START ' . $sequenceId);
        $this->addSql('ALTER INDEX idx_46b2b70e93cb796c RENAME TO IDX_9220ED1E93CB796C');
        $this->addSql('ALTER INDEX idx_46b2b70e4acc9a20 RENAME TO IDX_9220ED1E4ACC9A20');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE card_images_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE card_carousel_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER INDEX idx_9220ed1e4acc9a20 RENAME TO idx_46b2b70e4acc9a20');
        $this->addSql('ALTER INDEX idx_9220ed1e93cb796c RENAME TO idx_46b2b70e93cb796c');
    }
}
