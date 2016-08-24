<?php
/**
 * @package    BiBundle\Tests\Entity
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Tests\Entity;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use BiBundle\Entity\Exception\ValidatorException;
use BiBundle\Entity\Listener\WorkflowListener;
use BiBundle\Entity\User;
use BiBundle\Entity\UserContact;
use BiBundle\Tests\Traits\UserTrait;

/**
 * User contact tests
 */
class UserContactTest extends KernelTestCase
{
    use UserTrait;
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var User
     */
    private $user;

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

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
        $this->user = $this->createUser();
    }

    protected function createUser()
    {
        $user = new User();
        $password = 'test';
        $user->setFirstname('foo')->setLastname('bar')->setLogin('test')
            ->setEmail('some@domain.com')
            ->setPhone('79999999123')
            ->setStatus($this->getUserStatus())
            ->setRole($this->getUserRole())
            ->setPassword($password)
            ->setBirthDate(new \DateTime());

        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    /**
     * @expectedException \BiBundle\Entity\Exception\ValidatorException
     */
    public function testCreateContactWithoutUserFails()
    {
        $contact = new UserContact();
        $contact->setType(UserContact::TYPE_PHONE);
        $contact->setValue('79999999999');

        $this->em->persist($contact);
        $this->em->flush();
    }

    public function testCreateContact()
    {
        $user = $this->em->getRepository('BiBundle:User')->findOneBy(['login' => 'demo']);

        $contact = new UserContact();
        $contact->setType(UserContact::TYPE_PHONE);
        $contact->setValue('79999999999');
        $contact->setUser($user);

        $this->em->persist($contact);
        $this->em->flush();

        $this->assertNotEmpty($contact->getId());
    }

    /**
     * @expectedException \BiBundle\Entity\Exception\ValidatorException
     */
    public function testCreateContactWithInvalidType()
    {
        $user = $this->em->getRepository('BiBundle:User')->findOneBy(['login' => 'demo']);

        $contact = new UserContact();
        $contact->setType('foo');
        $contact->setValue('79999999999');
        $contact->setUser($user);

        $this->em->persist($contact);
        $this->em->flush();
    }

    /**
     * @return string
     */
    protected function getRandomPhone()
    {
        $phoneDigits = [];
        $i = 0;
        while ($i < 10) {
            $phoneDigits[] = rand(1, 9);
            $i++;
        }
        $phone = "7" . implode("", $phoneDigits);
        return $phone;
    }

    public function testCreateDefaultContact()
    {
        $user = $this->getUser();
        $phone = $this->getRandomPhone();

        $contact = new UserContact();
        $contact->setType(UserContact::TYPE_PHONE);
        $contact->setIsDefault(true);
        $contact->setValue($phone);
        $contact->setUser($user);

        $this->em->persist($contact);
        $this->em->flush();

        $this->assertEquals($contact->getValue(), $user->getPhone());
    }

    public function dataInvalidProvider()
    {
        return [
            [
                [
                    'type' => UserContact::TYPE_EMAIL,
                    'value' => 'some'
                ]
            ],
            [
                [
                    'type' => UserContact::TYPE_PHONE,
                    'value' => '83434'
                ]
            ]
        ];
    }

    /**
     * @dataProvider dataInvalidProvider
     * @param array $data
     * @expectedException \BiBundle\Entity\Exception\ValidatorException
     */
    public function testContactWithInvalidData(array $data)
    {
        $user = $this->getUser();

        $contact = new UserContact();
        $contact->setType($data['type']);
        $contact->setValue($data['value']);
        $contact->setUser($user);

        $this->em->persist($contact);
        $this->em->flush();
    }

    public function testContactPhonePlusIsCut()
    {
        $user = $this->getUser();
        $phone = $this->getRandomPhone();
        $phonePlus = "+" . $phone;

        $contact = new UserContact();
        $contact->setType(UserContact::TYPE_PHONE);
        $contact->setValue($phonePlus);
        $contact->setUser($user);

        $this->em->persist($contact);
        $this->em->flush();

        $this->assertTrue($phone === $contact->getValue(), "phone is not saved correctly");
        $this->assertRegExp("/^([0-9]+){11}$/", $contact->getValue(), "phone is not saved correctly");
    }

    protected function tearDown()
    {
        // delete users
        $q = $this->em->createQuery("DELETE FROM BiBundle\Entity\User u WHERE u.login NOT IN (:login)");
        $q->setParameter('login', ['demo', 'rose']);
        $q->execute();
        // delete contacts
        $c = $this->em->createQuery("DELETE FROM BiBundle\Entity\UserContact c");
        $c->execute();

        $this->em->close();

        parent::tearDown();
    }
}
