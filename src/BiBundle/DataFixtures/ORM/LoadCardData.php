<?php

namespace BiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use BiBundle\Entity\Card;

class LoadCardData extends AbstractFixture
    implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 4;
    }

    public function load(ObjectManager $manager)
    {

        /*
        $carousel = [
            '/images/cards/4-1.png',
            '/images/cards/4-2.png',
            '/images/cards/4-3.png',
            '/images/cards/4-4.png',
        ];

        $card = new Card();
        $card->setName('Выручка');
        $card->setDescription('Выручка от продажи готовых продукции, товаров');
        $card->setDescriptionLong(
            'Выручка – общая сумма денежных средств, полученная в результате реализации товаров и услуг за определенный промежуток времени. Общая выручка состоит из сумм, полученных предприятием в результате основной деятельности (реализации товара или услуги), инвестиционной деятельности (реализации внеоборотных активов и ценных бумаг) и финансовой деятельности предприятия. В данной карточке предусмотрены разные виды представлений выручки, фильтры по контрагентам, номенклатуре, договорам.'
        );
        $card->setRating(80);
        $card->setPrice(200);
        $card->setAuthor('Эттон');
        $card->setImage('/images/cards-preview/4-1.png');
        $card->setCarousel(implode(';', $carousel));

        $manager->persist($card);
        $manager->flush();

        $this->addReference('card-revenue', $card);

        $card = new Card();
        $card->setName('Дебиторская задолженность');
        $card->setDescription('Сумма долгов, причитающихся предприятию');
        $card->setDescriptionLong(
            'Дебиторской задолженностью понимается задолженность других организаций, работников и физических лиц данной организации. Дебиторская задолженность возникает в случае, если услуга (или товар) проданы, а денежные средства не получены. Дебиторская задолженность относится к оборотным активам компании вне зависимости от срока её погашения.'
        );
        $card->setRating(75);
        $card->setPrice(300);
        $card->setAuthor('Эттон');
        $card->setImage('/images/cards-preview/4-1.png');
        $card->setCarousel(implode(';', $carousel));

        $manager->persist($card);
        $manager->flush();

        $this->addReference('card-receivables', $card);

        $card = new Card();
        $card->setName('Кредиторская задолженность');
        $card->setDescription('Задолженность организации другим организациям и лицам');
        $card->setDescriptionLong(
            'Кредиторская задолженность – собственная финансовая задолженность организации, возникающая в течение отведенного договором срока для оплаты, а также вследствие отсутствия денежных средств для погашения долгов или недобросовестного выполнения договорных обязательств.'
        );
        $card->setRating(100);
        $card->setPrice(300);
        $card->setAuthor('Эттон');
        $card->setImage('/images/cards-preview/4-1.png');
        $card->setCarousel(implode(';', $carousel));

        $manager->persist($card);
        $manager->flush();

        $this->addReference('card-payable', $card);
        */
    }
}