<?php

namespace BiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use BiBundle\Entity\UserStatus;

class LoadUserStatusData extends AbstractFixture
    implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        $data = [
            UserStatus::STATUS_ACTIVE => 'Активен',
            UserStatus::STATUS_BLOCKED => 'Заблокирован',
            UserStatus::STATUS_DELETED => 'Удален',
            UserStatus::STATUS_REGISTERED => 'Зарегистрирован'
        ];
        $statusActive = null;

        foreach ($data as $code => $name) {
            $status = new UserStatus();
            $status->setName($name);
            $status->setCode($code);
            if ($code == UserStatus::STATUS_ACTIVE) {
                $statusActive = $status;
            }

            $manager->persist($status);
        }

        $manager->flush();

        $this->addReference('status-active', $statusActive);
    }
}