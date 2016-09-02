<?php

namespace BiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use BiBundle\Entity\CardRepresentation;

class LoadCardRepresentationData extends AbstractFixture
    implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 7;
    }

    public function load(ObjectManager $manager)
    {

        $cardRepresentation = new CardRepresentation();
        $cardRepresentation->setCard($this->getReference('card-demo'));
        $cardRepresentation->setRepresentation($this->getReference('representation-diagram'));
        $manager->persist($cardRepresentation);

        $cardRepresentation = new CardRepresentation();
        $cardRepresentation->setCard($this->getReference('card-demo'));
        $cardRepresentation->setRepresentation($this->getReference('representation-line'));
        $manager->persist($cardRepresentation);

        $cardRepresentation = new CardRepresentation();
        $cardRepresentation->setCard($this->getReference('card-demo'));
        $cardRepresentation->setRepresentation($this->getReference('representation-pie'));
        $manager->persist($cardRepresentation);

        $manager->flush();

    }
}