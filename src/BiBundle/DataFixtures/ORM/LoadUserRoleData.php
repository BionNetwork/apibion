<?php
/**
 * @package    BiBundle\DataFixtures\ORM
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use BiBundle\Entity\UserRole;

class LoadUserRoleData extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 2;
    }

    public function load(ObjectManager $manager)
    {
        $data = [
            UserRole::ROLE_USER => 'Пользователь',
            UserRole::ROLE_ADMIN => 'Администратор'
        ];

        foreach ($data as $code => $name) {
            $role = new UserRole();
            $role->setName($code);
            $role->setTitle($name);

            $manager->persist($role);

            if(UserRole::ROLE_USER == $role->getName()) {
                $this->addReference('role-user', $role);
            }

            if(UserRole::ROLE_ADMIN == $role->getName()) {
                $this->addReference('role-admin', $role);
            }
        }

        $manager->flush();
    }
}