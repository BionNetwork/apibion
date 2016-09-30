<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Migrations\IrreversibleMigrationException;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160930141535 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('ALTER TABLE card ALTER rating TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE card ALTER price TYPE NUMERIC(10, 2)');
    }

    /**
     * @param Schema $schema
     * @throws IrreversibleMigrationException
     */
    public function down(Schema $schema)
    {
        throw new IrreversibleMigrationException();
    }
}
