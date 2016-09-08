<?php

namespace BiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use BiBundle\Entity\Argument;

class LoadArgumentData extends AbstractFixture
    implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 8;
    }

    public function load(ObjectManager $manager)
    {
        /*
        // Выручка
        $argument = new Argument();
        $argument->setCode('');
        $argument->setName('Организация');
        $argument->setDimension('');
        $argument->setCard($this->getReference('card-revenue'));
        $manager->persist($argument);

        $argument = new Argument();
        $argument->setCode('');
        $argument->setName('Выручка');
        $argument->setDimension('Y');
        $argument->setCard($this->getReference('card-revenue'));
        $manager->persist($argument);

        $argument = new Argument();
        $argument->setCode('');
        $argument->setName('Выручка без НДС');
        $argument->setDimension('Y');
        $argument->setCard($this->getReference('card-revenue'));
        $manager->persist($argument);

        $argument = new Argument();
        $argument->setCode('');
        $argument->setName('Услуги/Товар');
        $argument->setDimension('');
        $argument->setCard($this->getReference('card-revenue'));
        $manager->persist($argument);

        $argument = new Argument();
        $argument->setCode('');
        $argument->setName('Контрагент');
        $argument->setDimension('');
        $argument->setCard($this->getReference('card-revenue'));
        $manager->persist($argument);

        $argument = new Argument();
        $argument->setCode('');
        $argument->setName('Договор');
        $argument->setDimension('');
        $argument->setCard($this->getReference('card-revenue'));
        $manager->persist($argument);

        $argument = new Argument();
        $argument->setCode('');
        $argument->setName('Проект');
        $argument->setDimension('');
        $argument->setCard($this->getReference('card-revenue'));
        $manager->persist($argument);

        $argument = new Argument();
        $argument->setCode('');
        $argument->setName('Дата');
        $argument->setDimension('');
        $argument->setCard($this->getReference('card-revenue'));
        $manager->persist($argument);

        // Дебиторская задолженность
        $argument = new Argument();
        $argument->setCode('');
        $argument->setName('Организация');
        $argument->setDimension('');
        $argument->setCard($this->getReference('card-receivables'));
        $manager->persist($argument);

        $argument = new Argument();
        $argument->setCode('');
        $argument->setName('Сумма задолженности');
        $argument->setDimension('');
        $argument->setCard($this->getReference('card-receivables'));
        $manager->persist($argument);

        $argument = new Argument();
        $argument->setCode('');
        $argument->setName('Контрагент');
        $argument->setDimension('');
        $argument->setCard($this->getReference('card-receivables'));
        $manager->persist($argument);

        $argument = new Argument();
        $argument->setCode('');
        $argument->setName('Дата');
        $argument->setDimension('X');
        $argument->setCard($this->getReference('card-receivables'));
        $manager->persist($argument);

        // Кредиторская задолженность
        $argument = new Argument();
        $argument->setCode('');
        $argument->setName('Организация');
        $argument->setDimension('');
        $argument->setCard($this->getReference('card-payable'));
        $manager->persist($argument);

        $argument = new Argument();
        $argument->setCode('');
        $argument->setName('Сумма задолженности');
        $argument->setDimension('Y');
        $argument->setCard($this->getReference('card-payable'));
        $manager->persist($argument);

        $argument = new Argument();
        $argument->setCode('');
        $argument->setName('Контрагент');
        $argument->setDimension('');
        $argument->setCard($this->getReference('card-payable'));
        $manager->persist($argument);

        $argument = new Argument();
        $argument->setCode('');
        $argument->setName('Дата');
        $argument->setDimension('X');
        $argument->setCard($this->getReference('card-payable'));
        $manager->persist($argument);

        $manager->flush();
        */

    }
}