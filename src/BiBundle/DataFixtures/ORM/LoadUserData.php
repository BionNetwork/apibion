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
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use BiBundle\Entity\User;

class LoadUserData extends AbstractFixture
    implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstname('Иван');
        $user->setLastname('Иванов');
        $user->setLogin('administrator');
        $user->setEmail('administrator@etton.ru');
        $user->setPhone('79999999999');
        $user->setRole($this->getReference('role-admin'));
        $user->setStatus($this->getReference('status-active'));
        $user->setPassword('administrator');
        $user->setBirthDate(new \DateTime("now"));
        $user->setMailNotification(true);
        $user->setMustChangePasswd(false);
        $user->setIsActive(true);
        $user->setIsSuperuser(true);

        $demoUser = $this->createDemoUser();

        $manager->persist($user);
        $manager->persist($demoUser);

        $manager->flush();

        $this->addReference('user-admin', $user);
    }

    protected function createDemoUser()
    {
        $user = new User();
        $user->setFirstname('Петр');
        $user->setLastname('Петров');
        $user->setLogin('user');
        $user->setEmail('user@etton.ru');
        $user->setPhone('79999999990');
        $user->setRole($this->getReference('role-user'));
        $user->setStatus($this->getReference('status-active'));
        $user->setPassword('user');
        $user->setBirthDate(new \DateTime("now"));
        $user->setMailNotification(true);
        $user->setMustChangePasswd(false);
        $user->setIsActive(true);
        $user->setIsSuperuser(false);

        return $user;
    }

    public function getOrder()
    {
        return 3;
    }
}