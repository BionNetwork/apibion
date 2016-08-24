<?php
/**
 * @package    IntelliJ IDEA
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace BiBundle\Tests\Service;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use BiBundle\Entity\Project;
use BiBundle\Service\EntitySerializer;
use BiBundle\Entity\User;
use BiBundle\Tests\Traits\UserTrait;

class EntitySerializerTest extends KernelTestCase
{
    use UserTrait;
    /**
     * @var EntityManager
     */
    private $em;

    protected function setUp()
    {
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')->getManager();
    }

    public function testSerialize()
    {
        $user = new User();
        $user->setFirstname('foo')->setLastname('bar')->setLogin('test')
            ->setEmail('some@domain.com')
            ->setPassword('test')
            ->setBirthDate(new \DateTime());

        $serializer = new EntitySerializer($this->em);

        $data = $serializer->serialize($user);

        $this->assertNotEmpty($data);
        foreach (['firstname', 'lastname', 'login', 'email', 'password', 'birthDate'] as $key) {
            $this->assertArrayHasKey($key, $data);
            $this->assertEquals($user->{"get".ucfirst($key)}(), $data[$key]);
        }
        $this->assertNull($data['position']);
    }

    public function testSerializeWithCollection()
    {
        $project = new Project();
        $project->setName('test')->setDescription('test project');

        $project2 = new Project();
        $project2->setName('child')->setDescription('child project');

        $project->addChild($project2);
        $serializer = new EntitySerializer($this->em);

        $data = $serializer->serialize($project);

        $this->assertNotEmpty($data['children']);
        $this->assertInstanceOf('BiBundle\Entity\Project', $data['children'][0]);
    }

    public function testSerializeAsArrayWithoutPersist()
    {
        $user = new User();
        $user->setFirstname('foo')->setLastname('bar')->setLogin('test')
            ->setEmail('some@domain.com')
            ->setPassword('test')
            ->setBirthDate(new \DateTime());

        $serializer = new EntitySerializer($this->em);

        $data = $serializer->serializeAsArray($user);

        $this->assertCount(2, $data, 'Serialize as array should return entity and data');
        $this->assertEquals(get_class($user), $data[0]);
        $this->assertEmpty($data[1]);
    }

    public function testSerializeAsArrayWitPersist()
    {
        $user = new User();
        $user->setFirstname('foo')->setLastname('bar')->setLogin('test')
            ->setEmail('some@domain.com')
            ->setPhone('79999999123')
            ->setPassword('test')
            ->setStatus($this->getUserStatus())
            ->setRole($this->getUserRole())
            ->setBirthDate(new \DateTime());

        $serializer = new EntitySerializer($this->em);

        $this->em->persist($user);
        $this->em->flush();

        $data = $serializer->serializeAsArray($user);

        $this->assertCount(2, $data, 'Serialize as array should return entity and data');
        $this->assertEquals(get_class($user), $data[0]);
        $this->assertNotEmpty($data[1]);

        $data = $data[1];
        foreach (['firstname', 'lastname', 'login', 'email', 'password'] as $key) {
            $this->assertArrayHasKey($key, $data);
            $this->assertEquals($user->{"get".ucfirst($key)}(), $data[$key]);
        }
        $this->assertArrayHasKey('status_id', $data, 'field name should correspond to table field name');
        $this->assertArrayHasKey('role_id', $data, 'field name should correspond to table field name');
    }

    public function testDeserialize()
    {
        $serializer = new EntitySerializer($this->em);
        $userData = [
            'id' => 1,
            'firstname' => 'foo',
            'lastname' => 'bar',
            'login' => 'test',
            'email' => 'some@domain.com',
            'password' => 'test',
            'birthDate' => new \DateTime()
        ];
        $data = [
            '\BiBundle\Entity\User',
            $userData
        ];

        /** @var \BiBundle\Entity\User $user */
        $user = $serializer->deserialize($data);
        $this->assertInstanceOf('BiBundle\Entity\User', $user);
        $this->assertEquals($userData['firstname'], $user->getFirstname());
        $this->assertEquals($userData['lastname'], $user->getLastname());
        $this->assertEquals($userData['login'], $user->getLogin());
        $this->assertEquals($userData['password'], $user->getPassword());
        $this->assertEquals($userData['birthDate'], $user->getBirthDate());
    }

    public function testSerializaDateTime()
    {
        $serializer = new EntitySerializer($this->em);
        $date = new \DateTime();

        $dateSerialized = $serializer->serialize($date);
        $this->assertEquals($date, $dateSerialized, "Datetime object is not serialized correctly");
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