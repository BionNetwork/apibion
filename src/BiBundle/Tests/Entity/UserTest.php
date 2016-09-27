<?php
/**
 * @package    BiBundle\Tests\Entity
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use BiBundle\Entity\Listener\WorkflowListener;
use BiBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use BiBundle\Tests\Traits\UserTrait;

class UserTest extends KernelTestCase
{
    use UserTrait;
    /**
     * @var EntityManager
     */
    private $em;

    protected function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')->getManager();
        $this->container = static::$kernel->getContainer();

        $listener = $this->getMockBuilder(WorkflowListener::class)
            ->disableOriginalConstructor()
            ->setMethods(['saveChangeSet', 'getContainer'])
            ->getMock();

        $listener->expects($this->any())->method('getContainer')->will($this->returnValue($this->container));
        $listener->expects($this->any())->method('saveChangeSet')->will($this->returnValue(true));
        $this->container->set('workflow.listener', $listener);
    }

    public function testUserCreatedWithValidPassword()
    {
        $user = new User();
        $password = 'test';
        $user->setFirstname('foo')
            ->setLastname('bar')
            ->setLogin('test')
            ->setEmail('noreply@domain.com')
            ->setPhone('79999999120')
            ->setPassword($password)
            ->setStatus($this->getUserStatus())
            ->setRole($this->getUserRole())
            ->setBirthDate(new \DateTime());

        $this->em->persist($user);
        $this->em->flush();

        $security = $this->container->get('security.password_encoder');
        $this->assertTrue($security->isPasswordValid($user, $password));
    }


    protected function tearDown()
    {
        $q = $this->em->createQuery("DELETE FROM BiBundle\Entity\User u WHERE u.login NOT IN (:login)");
        $q->setParameter('login', ['user', 'administrator']);
        $q->execute();

        $this->em->close();

        parent::tearDown();
    }
}