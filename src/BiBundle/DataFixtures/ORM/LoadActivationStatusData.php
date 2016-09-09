<?php

namespace BiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use BiBundle\Entity\ActivationStatus;

class LoadActivationStatusData extends AbstractFixture
    implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 5;
    }

    public function load(ObjectManager $manager)
    {
        $data = [
            ActivationStatus::STATUS_PENDING => 'В процессе',
            ActivationStatus::STATUS_ACTIVE => 'Активен',
            ActivationStatus::STATUS_DELETED => 'Удален',
        ];

        foreach ($data as $code => $name) {
            $status = new ActivationStatus();
            $status->setName($name);
            $status->setCode($code);
            $manager->persist($status);
        }

        $manager->flush();

    }
}