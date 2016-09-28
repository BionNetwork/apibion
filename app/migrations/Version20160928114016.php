<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160928114016 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('SELECT setval(\'public.argument_id_seq\', 16, true)');
        $this->addSql('SELECT setval(\'public.card_id_seq\', 23, true)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->throwIrreversibleMigrationException();
    }
}
