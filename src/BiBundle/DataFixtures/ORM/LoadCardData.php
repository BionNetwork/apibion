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
        $card = new Card();
        $card->setName('demo');
        $card->setType('income');
        $card->setRating(44);
        $card->setPrice(500);

        $manager->persist($card);
        $manager->flush();

        $this->addReference('card-demo', $card);

    }
}