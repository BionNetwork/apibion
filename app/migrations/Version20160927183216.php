<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160927183216 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE ext_translations (id SERIAL NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(255) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX translations_lookup_idx ON ext_translations (locale, object_class, foreign_key)');
        $this->addSql('CREATE UNIQUE INDEX lookup_unique_idx ON ext_translations (locale, object_class, field, foreign_key)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE ext_translations');
    }
}
