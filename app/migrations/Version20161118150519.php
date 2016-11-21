<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161118150519 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE filter_types ADD type VARCHAR(255) DEFAULT NULL');

        $this->addSql('TRUNCATE TABLE filter_types CASCADE');

        $items = [
            [
                'name' => 'sliderbar (интервал год)',
                'sort' => 10,
                'type' => 'slidebar'
            ],
            [
                'name' => 'checkbox (выбрать все)',
                'sort' => 20,
                'type' => 'checkbox'
            ],
            [
                'name' => 'checkbox (multiselect)',
                'sort' => 30,
                'type' => 'checkbox'
            ],
            [
                'name' => 'checkbox (select)',
                'sort' => 40,
                'type' => 'checkbox'
            ],
            [
                'name' => 'combobox (выбрать все)',
                'sort' => 50,
                'type' => 'combobox'
            ],
            [
                'name' => 'combobox (multiselect)',
                'sort' => 60,
                'type' => 'combobox'
            ],
            [
                'name' => 'combobox (select)',
                'sort' => 70,
                'type' => 'combobox'
            ],
            [
                'name' => 'calendar',
                'sort' => 80,
                'type' => 'calendar'
            ],
            [
                'name' => 'radiobutton',
                'sort' => 90,
                'type' => 'radiobutton'
            ]
        ];
        foreach ($items as $item) {
            $this->addSql("INSERT INTO filter_types (name, sort, type) VALUES (?, ?, ?)", [
                $item['name'], $item['sort'], $item['type']
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

        $this->addSql('ALTER TABLE filter_types DROP type');
    }
}
