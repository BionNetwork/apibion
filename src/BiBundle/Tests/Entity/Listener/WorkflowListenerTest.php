<?php
/**
 * @package    BiBundle\Tests\Entity\Listener
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */


namespace BiBundle\Tests\Entity\Listener;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use BiBundle\Entity\Listener\WorkflowListener;
use BiBundle\Entity\UserContact;
use BiBundle\Entity\User;
use BiBundle\Tests\Traits\UserTrait;

class WorkflowListenerTest extends KernelTestCase
{
    use UserTrait;
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var WorkflowListener
     */
    private $listener;

    protected function setUp()
    {
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
        $this->em = $this->container
            ->get('doctrine')->getManager();

        $listener = $this->getMockBuilder(WorkflowListener::class)
            ->disableOriginalConstructor()
            ->setMethods(['saveChangeSet', 'getContainer', 'saveEvent'])
            ->getMock();

        $listener->expects($this->any())->method('getContainer')->will($this->returnValue($this->container));
        $listener->expects($this->any())->method('saveChangeSet')->will($this->returnValue(true));
        $this->container->set('workflow.listener', $listener);
        $this->listener = $listener;
    }

    public function testGetChangeSet()
    {
        $user = new User();
        $user->setFirstname('foo')->setLastname('bar')->setLogin('test' . rand(100000, 10000000))
            ->setEmail(rand(1, 100000) . '@domain.com')
            ->setPassword('test')
            ->setPhone('7' . rand(1111111111, 9999999999))
            ->setStatus($this->getUserStatus())
            ->setRole($this->getUserRole())
            ->setBirthDate(new \DateTime());

        $contact = new UserContact();
        $contact->setType('phone');
        $contact->setValue('7' . rand(1111111111, 9999999999));
        $contact->setUser($user);

        $this->em->persist($user);
        $this->em->flush();

        $this->assertNotNull($user->getId());

        // set another contact
        $contact2 = new UserContact();
        $contact2->setType('phone');
        $contact2->setValue('71111111113');
        $contact2->setUser($user);

        $this->em->persist($user);
        $this->em->flush();

        $changeSet = $this->listener->getChangeSet($user, $this->em->getUnitOfWork());
        $this->assertNotEmpty($changeSet, "Change set should not be empty");
        foreach ($changeSet as $value) {
            $this->assertTrue(count($value) == 2);
            if (!$value[0] instanceof \DateTime && is_object($value[0])) {
                $this->fail("Value should not be object " . var_export($value[0], true));
            }
            if (!$value[1] instanceof \DateTime && is_object($value[1])) {
                $this->fail("Value should not be object " . var_export($value[1], true));
            }
        }
    }

    protected function tearDown()
    {
        $q = $this->em->createQuery("DELETE FROM BiBundle\Entity\User u WHERE u.login NOT IN (:login)");
        $q->setParameter('login', ['demo', 'rose']);
        $q->execute();

        $this->em->close();

        parent::tearDown();
    }
}
