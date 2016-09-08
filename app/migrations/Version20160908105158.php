<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160908105158 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
$query = <<<EOD
INSERT INTO "public"."activation_status" VALUES ('4', 'В процессе', 'pending')+
INSERT INTO "public"."activation_status" VALUES ('5', 'Активен', 'active')+
INSERT INTO "public"."activation_status" VALUES ('6', 'Удален', 'deleted')+
INSERT INTO "public"."user_roles" VALUES ('1', 'USER', 'Пользователь')+
INSERT INTO "public"."user_roles" VALUES ('2', 'ADMIN', 'Администратор')+
INSERT INTO "public"."user_statuses" VALUES ('1', 'Активен', 'active')+
INSERT INTO "public"."user_statuses" VALUES ('2', 'Заблокирован', 'blocked')+
INSERT INTO "public"."user_statuses" VALUES ('3', 'Удален', 'deleted')+
INSERT INTO "public"."user_statuses" VALUES ('4', 'Зарегистрирован', 'registered')+
INSERT INTO "public"."users" VALUES ('1', '1', '2', 'Иван', 'Иванов', null, 'administrator', 'administrator@etton.ru', '$2y$13$46n4S7EQcW31da8QwkiyaOOgT5EzuRbSpr6z0AlBPjnsRl188BOhy', '2016-09-08', null, null, null, '79999999999', null, null, null, 't', 'f', null, 't', 'f', 'f', null, 't')+
INSERT INTO "public"."users" VALUES ('2', '1', '1', 'Петр', 'Петров', null, 'user', 'user@etton.ru', '$2y$13\$wU869dagw9DE22uvXngRU.7ho3CW..VueKTDU81BkiHBJzP/Ehoju', '2016-09-08', null, null, null, '79999999990', null, null, null, 't', 'f', null, 't', 'f', 'f', null, 'f')+
INSERT INTO "public"."card" VALUES ('21', 'Выручка', 'Выручка от продажи готовых продукции, товаров', 'Выручка – общая сумма денежных средств, полученная в результате реализации товаров и услуг за определенный промежуток времени. Общая выручка состоит из сумм, полученных предприятием в результате основной деятельности (реализации товара или услуги), инвестиционной деятельности (реализации внеоборотных активов и ценных бумаг) и финансовой деятельности предприятия. В данной карточке предусмотрены разные виды представлений выручки, фильтры по контрагентам, номенклатуре, договорам.', '80', 'Эттон', '/images/cards-preview/4-1.png', '/images/cards/4-1.png;/images/cards/4-2.png;/images/cards/4-3.png;/images/cards/4-4.png', null, '200', null, null)+
INSERT INTO "public"."card" VALUES ('22', 'Дебиторская задолженность', 'Сумма долгов, причитающихся предприятию', 'Дебиторской задолженностью понимается задолженность других организаций, работников и физических лиц данной организации. Дебиторская задолженность возникает в случае, если услуга (или товар) проданы, а денежные средства не получены. Дебиторская задолженность относится к оборотным активам компании вне зависимости от срока её погашения.', '75', 'Эттон', '/images/cards-preview/4-1.png', '/images/cards/4-1.png;/images/cards/4-2.png;/images/cards/4-3.png;/images/cards/4-4.png', null, '300', null, null)+
INSERT INTO "public"."card" VALUES ('23', 'Кредиторская задолженность', 'Задолженность организации другим организациям и лицам', 'Кредиторская задолженность – собственная финансовая задолженность организации, возникающая в течение отведенного договором срока для оплаты, а также вследствие отсутствия денежных средств для погашения долгов или недобросовестного выполнения договорных обязательств.', '100', 'Эттон', '/images/cards-preview/4-1.png', '/images/cards/4-1.png;/images/cards/4-2.png;/images/cards/4-3.png;/images/cards/4-4.png', null, '300', null, null)+
INSERT INTO "public"."argument" VALUES ('1', '21', 'Организация', '', '', null, null, null)+
INSERT INTO "public"."argument" VALUES ('2', '21', 'Выручка', '', 'Y', null, null, null)+
INSERT INTO "public"."argument" VALUES ('3', '21', 'Выручка без НДС', '', 'Y', null, null, null)+
INSERT INTO "public"."argument" VALUES ('4', '21', 'Услуги/Товар', '', '', null, null, null)+
INSERT INTO "public"."argument" VALUES ('5', '21', 'Контрагент', '', '', null, null, null)+
INSERT INTO "public"."argument" VALUES ('6', '21', 'Договор', '', '', null, null, null)+
INSERT INTO "public"."argument" VALUES ('7', '21', 'Проект', '', '', null, null, null)+
INSERT INTO "public"."argument" VALUES ('8', '21', 'Дата', '', '', null, null, null)+
INSERT INTO "public"."argument" VALUES ('9', '22', 'Организация', '', '', null, null, null)+
INSERT INTO "public"."argument" VALUES ('10', '22', 'Сумма задолженности', '', '', null, null, null)+
INSERT INTO "public"."argument" VALUES ('11', '22', 'Контрагент', '', '', null, null, null)+
INSERT INTO "public"."argument" VALUES ('12', '22', 'Дата', '', 'X', null, null, null)+
INSERT INTO "public"."argument" VALUES ('13', '23', 'Организация', '', '', null, null, null)+
INSERT INTO "public"."argument" VALUES ('14', '23', 'Сумма задолженности', '', 'Y', null, null, null)+
INSERT INTO "public"."argument" VALUES ('15', '23', 'Контрагент', '', '', null, null, null)+
INSERT INTO "public"."argument" VALUES ('16', '23', 'Дата', '', 'X', null, null, null)+
INSERT INTO "public"."representation" VALUES ('1', 'Диаграмма', 'diagram', null, null)+
INSERT INTO "public"."representation" VALUES ('2', 'Столбцы', 'column', null, null)+
INSERT INTO "public"."representation" VALUES ('3', 'Линии', 'line', null, null)+
INSERT INTO "public"."representation" VALUES ('4', 'Круговая', 'pie', null, null)+
INSERT INTO "public"."representation" VALUES ('5', 'Воронка', 'funnel', null, null)+
INSERT INTO "public"."card_representation" VALUES ('1', null, '21', '1', null, null)+
INSERT INTO "public"."card_representation" VALUES ('2', null, '21', '3', null, null)+
INSERT INTO "public"."card_representation" VALUES ('3', null, '21', '4', null, null)+
INSERT INTO "public"."card_representation" VALUES ('4', null, '22', '1', null, null)+
INSERT INTO "public"."card_representation" VALUES ('5', null, '22', '3', null, null)+
INSERT INTO "public"."card_representation" VALUES ('6', null, '22', '4', null, null)+
INSERT INTO "public"."card_representation" VALUES ('7', null, '23', '1', null, null)+
INSERT INTO "public"."card_representation" VALUES ('8', null, '23', '3', null, null)+
INSERT INTO "public"."card_representation" VALUES ('9', null, '23', '4', null, null)
EOD;
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        foreach(explode('+', $query) as $item) {
            $this->addSql($item);
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
    }
}
