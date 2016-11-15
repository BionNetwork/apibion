<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161115155915 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $items = [
            [
                'name' => 'sliderbar (интервал год)',
                'sort' => 10
            ],
            [
                'name' => 'checkbox (выбрать все)',
                'sort' => 20
            ],
            [
                'name' => 'checkbox (multiselect)',
                'sort' => 30
            ],
            [
                'name' => 'checkbox (select)',
                'sort' => 40
            ],
            [
                'name' => 'combobox (выбрать все)',
                'sort' => 50
            ],
            [
                'name' => 'combobox (multiselect)',
                'sort' => 60
            ],
            [
                'name' => 'combobox (select)',
                'sort' => 70
            ],
            [
                'name' => 'calendar',
                'sort' => 80
            ],
            [
                'name' => 'radiobutton',
                'sort' => 90
            ]
        ];
        foreach ($items as $item) {
            $this->addSql("INSERT INTO filter_types (name, sort) VALUES (?, ?)", [
                $item['name'], $item['sort']
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

        $this->addSql('DELETE FROM filter_types');
    }
}
