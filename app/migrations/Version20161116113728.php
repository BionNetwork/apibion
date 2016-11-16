<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161116113728 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("DELETE FROM charts");
        $data = [
            [
                'name' => 'карта РФ',
                'code' => 'map-russia'
            ],
            [
                'name' => 'карта мира',
                'code' => 'map-world'
            ],
            [
                'name' => 'круговая диаграмма',
                'code' => 'pie'
            ],
            [
                'name' => 'диаграмма с накоплением',
                'code' => 'diagram'
            ],
            [
                'name' => 'столбчатая диаграмма',
                'code' => 'column'
            ],
            [
                'name' => 'кривая',
                'code' => 'curve'
            ],
            [
                'name' => 'воронка',
                'code' => 'funnel'
            ],
            [
                'name' => 'space-матрица',
                'code' => 'space-matrix'
            ]
        ];
        foreach ($data as $item) {
            $this->addSql("INSERT INTO charts (name, code, created_on, updated_on) VALUES (?,?, now(), now())", [
                $item['name'], $item['code']
            ]);
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        // no back transaction
    }
}
