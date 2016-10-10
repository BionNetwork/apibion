<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161010155654 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE card_carousel_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE file_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE card_carousel_image (id INT NOT NULL, file_id INT DEFAULT NULL, card_id INT DEFAULT NULL, priority INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_46B2B70E93CB796C ON card_carousel_image (file_id)');
        $this->addSql('CREATE INDEX IDX_46B2B70E4ACC9A20 ON card_carousel_image (card_id)');
        $this->addSql('CREATE TABLE file (id INT NOT NULL, path TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE card_carousel_image ADD CONSTRAINT FK_46B2B70E93CB796C FOREIGN KEY (file_id) REFERENCES file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE card_carousel_image ADD CONSTRAINT FK_46B2B70E4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE card_carousel_image DROP CONSTRAINT FK_46B2B70E93CB796C');
        $this->addSql('DROP SEQUENCE card_carousel_image_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE file_id_seq CASCADE');
        $this->addSql('DROP TABLE card_carousel_image');
        $this->addSql('DROP TABLE file');
    }
}
