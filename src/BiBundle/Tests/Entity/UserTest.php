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
        $user->setFirstname('foo')->setLastname('bar')->setLogin('test')
            ->setEmail('some@domain.com')
            ->setPhone('79999999123')
            ->setPassword($password)
            ->setStatus($this->getUserStatus())
            ->setRole($this->getUserRole())
            ->setBirthDate(new \DateTime());

        $this->em->persist($user);
        $this->em->flush();

        $security = $this->container->get('security.password_encoder');
        $this->assertTrue($security->isPasswordValid($user, $password));
    }

    /**
     * @expectedException \BiBundle\Entity\Exception\ValidatorException
     * @expectedExceptionMessage This value should not be blank.
     */
    public function testUserWithoutPhoneIsNotCreated()
    {
        $user = new User();
        $password = 'test';
        $user->setFirstname('foo')->setLastname('bar')->setLogin('test')
            ->setPassword($password)
            ->setBirthDate(new \DateTime());

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @expectedException \BiBundle\Entity\Exception\ValidatorException
     * @expectedExceptionMessage This value is not a valid email address.
     */
    public function testUserWithInvalidEmailIsNotCreated()
    {
        $user = new User();
        $password = 'test';
        $user->setFirstname('foo')->setLastname('bar')->setLogin('test_user')
            ->setEmail('12345')
            ->setPassword($password)
            ->setBirthDate(new \DateTime());

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @dataProvider userInvalidDataProvider
     * @param array $data
     * @expectedException \BiBundle\Entity\Exception\ValidatorException
     */
    public function testUserWithInvalidDataIsNotCreated($data)
    {
        $user = new User($data);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function userInvalidDataProvider()
    {
        $users = [
            [
                [
                    'firstname' => 'Josh'
                ],
            ],
            [
                [
                    'firstname' => 'Josh', 'lastname' => 'Wellington'
                ],
            ],
            [
                [
                    'firstname' => 'Josh', 'lastname' => 'Wellington', 'login' => 'wellington'
                ],
            ],
            [
                [
                    'firstname' => 'Josh', 'lastname' => 'Wellington', 'login' => 'wellington',
                    'email' => 'wellington@somedomain'
                ],
            ],
            [
                [
                    'firstname' => 'Alexander', 'lastname' => 'Pierce', 'login' => 'demo',
                    'email' => 'noreply@etton.ru', 'birthDate' => new \DateTime()
                ]// user already exists
            ]
        ];
        return $users;
    }

    public function testUserPasswordIsNotChangedWhileUpdatingUser()
    {
        $user = new User();
        $password = 'test';
        $user->setFirstname('foo')->setLastname('bar')->setLogin('test')
            ->setEmail('some@domain.com')
            ->setPhone('79999999123')
            ->setPassword($password)
            ->setStatus($this->getUserStatus())
            ->setRole($this->getUserRole())
            ->setBirthDate(new \DateTime());

        $this->em->persist($user);
        $this->em->flush();

        $user->setFirstname('foo2');
        $this->em->persist($user);
        $this->em->flush();

        $security = $this->container->get('security.password_encoder');
        $this->assertTrue($security->isPasswordValid($user, $password), "Password was changed while updating");
    }

    public function testUserPasswordCanBeChanged()
    {
        $user = new User();
        $password = 'test';
        $password2 = 'test2';
        $user->setFirstname('foo')->setLastname('bar')->setLogin('test')
            ->setEmail('some@domain.com')
            ->setPhone('79999999123')
            ->setPassword($password)
            ->setStatus($this->getUserStatus())
            ->setRole($this->getUserRole())
            ->setBirthDate(new \DateTime());

        $this->em->persist($user);
        $this->em->flush();

        $user->setPassword($password2);
        $this->em->persist($user);
        $this->em->flush();

        $security = $this->container->get('security.password_encoder');
        $this->assertTrue($security->isPasswordValid($user, $password2), "Password was not changed");
        $this->assertNotEmpty($user->getPasswdChangedOn());
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