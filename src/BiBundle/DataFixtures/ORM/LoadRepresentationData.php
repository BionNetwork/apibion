<?php

namespace BiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use BiBundle\Entity\Representation;

class LoadRepresentationData extends AbstractFixture
    implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 6;
    }

    public function load(ObjectManager $manager)
    {
        /*
        $representation = new Representation();
        $representation->setCode('diagram');
        $representation->setName('Диаграмма');
        $this->addReference('representation-diagram', $representation);
        $manager->persist($representation);

        $representation = new Representation();
        $representation->setCode('column');
        $representation->setName('Столбцы');
        $this->addReference('representation-column', $representation);
        $manager->persist($representation);

        $representation = new Representation();
        $representation->setCode('line');
        $representation->setName('Линии');
        $this->addReference('representation-line', $representation);
        $manager->persist($representation);

        $representation = new Representation();
        $representation->setCode('pie');
        $representation->setName('Круговая');
        $this->addReference('representation-pie', $representation);
        $manager->persist($representation);

        $representation = new Representation();
        $representation->setCode('funnel');
        $representation->setName('Воронка');
        $this->addReference('representation-funnel', $representation);
        $manager->persist($representation);

        $manager->flush();
        */

    }
}