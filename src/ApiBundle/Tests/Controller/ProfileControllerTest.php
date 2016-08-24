<?php
/**
 * @package    ApiBundle\Tests\Controller;
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */
namespace ApiBundle\Tests\Controller;

use BiBundle\Entity\User;

class ProfileControllerTest extends ControllerTestCase
{
    protected $apiTokenAuthentication = true;

    public static function tearDownAfterClass()
    {
        static::$em->createQuery("DELETE FROM BiBundle\Entity\Member m")->execute();
        static::$em->createQuery("DELETE FROM BiBundle\Entity\Project p")->execute();
        $q = static::$em->createQuery("DELETE FROM BiBundle\Entity\User u WHERE u.login NOT IN (:login)");
        $q->setParameter('login', ['demo', 'rose']);
        $q->execute();
        static::$em->close();
    }

    protected function apiTokenAuthenticated()
    {
        $user = $this->testCreateUser();
        $firewall = 'api';
        $this->authenticate($firewall, $user->getLogin());
    }

    public function testProjectCanBeCreated()
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            '/api/v1/projects',
            [
                'name' => 'test project',
                'fullname' => 'my new project',
                'assigned_to' => $this->getUser()->getId(),
                'due_date' => date("d.m.Y")
            ]);
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $content = $this->getResponseContent($response);
        $this->assertArrayHasKey('id', $content['data']);
        return $content['data']['id'];
    }

    /**
     * @depends testProjectCanBeCreated
     * @param $id
     */
    public function testProjectCanBeAddedToTopList($id)
    {
        $client = $this->getClient();
        $client->request(
            'PUT',
            "/api/v1/projects/{$id}/starred",
            []
        );
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $this->assert204($response);
    }

    /**
     * @depends testProjectCanBeCreated
     * @param $id
     */
    public function testProjectCanBeRemovedFromTopList($id)
    {
        $client = $this->getClient();
        $client->request(
            'DELETE',
            "/api/v1/projects/{$id}/starred",
            []
        );
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $this->assert204($response);
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

    public function testCreateUser()
    {
        $user = new User();
        $password = 'test';
        $random = rand(1, 10000);

        $user
            ->setFirstname('name_' . $random)
            ->setLastname('last_name_' . $random)
            ->setMiddlename('middle_name_' . $random)
            ->setLogin('login_' . $random)
            ->setEmail(sprintf('email_%s@domain.com', $random))
            ->setPhone($this->getRandomPhone())
            ->setStatus($this->getUserStatus())
            ->setRole($this->getUserRole())
            ->setPassword($password)
            ->setBirthDate(\DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d 00:00:00")));

        $service = $this->client->getContainer()->get('user.service');
        $service->save($user);
        $this->assertNotNull($user->getId());
        return $user;
    }

    /**
     * @depends testCreateUser
     * @param User $user
     * @return mixed
     */
    public function testUserCanLogin(User $user)
    {
        $firewall = 'api';
        $this->authenticate($firewall, $user->getLogin());
        $session = $this->client->getContainer()->get('session');
        /** @var \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken $data */
        $data = unserialize($session->get('_security_'.$firewall));
        $this->assertInstanceOf('\Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken', $data);
        /** @var User $user */
        $sessionUser = $data->getUser();
        $this->assertInstanceOf('\BiBundle\Entity\User', $sessionUser);
        $this->assertEquals($user->getId(), $sessionUser->getId());
        return $sessionUser;
    }

    protected function authUser(User $user)
    {
        $firewall = 'api';
        $this->authenticate($firewall, $user->getLogin());
    }

    /**
     * @depends testCreateUser
     * @param User $user
     */
    public function testGetProfile(User $user)
    {
        $this->authUser($user);

        $client = $this->getClient();
        $client->request(
            'GET',
            "/api/v1/profile"
        );
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $data = $this->getResponseContent($response);
        $this->assertArrayHasKey('data', $data);
        $data = $data['data'];
        foreach (
            [
                'id', 'avatar', 'avatar_small', 'birth_date',
                'email', 'first_name', 'last_name', 'middle_name',
                'login', 'phones', 'phone', 'position', 'organizations'
            ] as $key) {
            $this->assertArrayHasKey($key, $data);
        }

        $this->assertEquals($user->getId(), $data['id'], "user id is not the same");
        $this->assertEquals($user->getAvatar(), $data['avatar']);
        $this->assertEquals($user->getAvatarSmall(), $data['avatar_small']);
        $this->assertEquals($user->getBirthDate()->getTimestamp(), $data['birth_date']);
        $this->assertEquals($user->getEmail(), $data['email']);
        $this->assertEquals($user->getFirstname(), $data['first_name']);
        $this->assertEquals($user->getLastname(), $data['last_name']);
        $this->assertEquals($user->getMiddlename(), $data['middle_name']);
        $this->assertEquals($user->getLogin(), $data['login']);
        $this->assertEquals($user->getPhone(), $data['phone']);
        $this->assertEquals($user->getPosition(), $data['position']);
        $this->assertEquals($user->getOrganizations()->toArray(), $data['organizations']);
    }

    /**
     * @depends testCreateUser
     * @param User $user
     */
    public function testPutProfileWithInvalidData(User $user)
    {
        $this->authUser($user);

        $client = $this->getClient();
        $client->request(
            'PUT',
            "/api/v1/profile"
        );
        $response = $client->getResponse();
        $this->assert400($response);
    }

    /**
     * @depends testCreateUser
     * @param User $user
     */
    public function testPutProfile(User $user)
    {
        $this->authUser($user);

        $this->assertNotNull($user->getMiddlename());
        $data = [
            'firstname' => 'new firstname',
            'lastname' => 'new lastname',
            'email' => 'some@mail.ru',
            'birthDate' => date("d.m.Y"),
            'position' => $user->getPosition()
        ];
        $client = $this->getClient();
        $client->request(
            'PUT',
            "/api/v1/profile",
            $data
        );
        $response = $client->getResponse();
        $this->assert204($response);
        $user = $this->getUser($user->getLogin());
        $this->assertEquals($data['firstname'], $user->getFirstname());
        $this->assertEquals($data['lastname'], $user->getLastname());
        $this->assertEquals(null, $user->getMiddlename());// field was dropped
        $this->assertEquals($data['email'], $user->getEmail());
        $this->assertEquals($data['birthDate'], $user->getBirthDate()->format('d.m.Y'));
        $this->assertEquals($data['position'], $user->getPosition());
    }
}