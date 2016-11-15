<?php

namespace BiBundle\DataFixtures\ORM;

use BiBundle\Entity\CardCategory;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadCardCategoryData extends AbstractFixture
    implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 10;
    }

    public function load(ObjectManager $manager)
    {
        $categories = [
            'Финансы',
            'Производство'
        ];
        foreach ($categories as $category) {
            $item = new CardCategory();
            $item->setName($category);
            $manager->persist($item);
        }
        $manager->flush();
    }
}