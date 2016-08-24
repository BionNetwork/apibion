<?php
/**
 * @package    ApiBundle\Tests\Controller
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\Tests\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Response;
use BiBundle\Entity\UserRole;
use BiBundle\Entity\UserStatus;

class ControllerTestCase extends WebTestCase
{
    protected $auth;
    /**
     * @var Client
     */
    protected $client;
    /**
     * Authentication by api key
     *
     * @var bool
     */
    protected $apiKeyAuthentication = false;
    /**
     * Authentication by api token
     *
     * @var bool
     */
    protected $apiTokenAuthentication = false;
    /**
     * @var EntityManager
     */
    protected static $em;

    public static function setUpBeforeClass()
    {
        self::bootKernel();
        static::$em = static::$kernel->getContainer()->get('doctrine')->getManager();
    }

    public function setUp()
    {
        $this->client = static::createClient();
        if ($this->apiKeyAuthentication) {
            $this->apiKeyAuthenticated();
        }
        if ($this->apiTokenAuthentication) {
            $this->apiTokenAuthenticated();
        }
    }

    /**
     * Get current user from database
     *
     * @param string $login
     * @return null|\BiBundle\Entity\User
     */
    protected function getUser($login = 'demo')
    {
        $container = $this->client->getContainer();
        $repository = $container->get('repository.user_repository');
        $user = $repository->findOneBy(['login' => $login]);
        return $user;
    }

    /**
     * @param string $code
     * @return null|object|UserStatus
     */
    protected function getUserStatus($code = UserStatus::STATUS_ACTIVE)
    {
        $container = $this->client->getContainer();
        $repository = $container->get('repository.user_repository');
        return $repository->getStatus($code);
    }

    /**
     * @param string $code
     * @return null|object|UserRole
     */
    protected function getUserRole($code = UserRole::ROLE_USER)
    {
        $container = $this->client->getContainer();
        $repository = $container->get('repository.user_repository');
        return $repository->getRole($code);
    }

    /**
     * Authenticate by key
     */
    protected function apiKeyAuthenticated()
    {
        $firewall = 'api_auth';
        // default user to log in
        $login = 'demo';
        $this->authenticate($firewall, $login);
    }

    /**
     * Authenticate by token
     */
    protected function apiTokenAuthenticated()
    {
        $firewall = 'api';
        // default user to log in
        $login = 'demo';
        $this->authenticate($firewall, $login);
    }

    /**
     * Authentication
     *
     * @param $firewall
     * @param $login
     */
    protected function authenticate($firewall, $login)
    {
        $session = $this->client->getContainer()->get('session');
        if ('api' == $firewall) {
            $user = $this->getUser($login);
            $roles = array_merge($user->getRoles(), ['ROLE_API']);
        } else {
            $roles = ['ROLE_API'];
            $user = $login;
        }

        $token = new UsernamePasswordToken($user, null, $firewall, $roles);
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    /**
     * Get response
     *
     * @param Response $response
     * @return mixed
     */
    protected function getResponseContent(Response $response)
    {
        $content = json_decode($response->getContent(), true);
        $this->assertNotEmpty($content, "response content should not be empty");
        return $content;
    }

    /**
     * Assert exception
     *
     * @param Response $response
     * @param null $code
     */
    protected function assertException(Response $response, $code = null)
    {
        $content = $this->getResponseContent($response);
        $this->assertArrayHasKey('success', $content, "exception should contain success key");
        $this->assertEquals(false, $content['success'], sprintf("exception should have success key to false, but %s given", $content['success']));
        $this->assertArrayHasKey('exception', $content, "exception should contain exception key");
        if (null !== $code) {
            $this->assertEquals($code, $content['exception']['code'], "exception code is not valid");
        }
    }

    protected function assert404(Response $response)
    {
        $this->assertEquals(404, $response->getStatusCode(), $response->headers);
    }

    protected function assert403(Response $response)
    {
        $this->assertEquals(403, $response->getStatusCode(), $response->headers);
    }

    protected function assert401(Response $response)
    {
        $this->assertEquals(401, $response->getStatusCode(), $response->headers);
    }

    protected function assert400(Response $response)
    {
        $this->assertEquals(400, $response->getStatusCode(), $response->headers);
    }

    protected function assert204(Response $response)
    {
        $this->assertEquals(204, $response->getStatusCode(), $response->headers);
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}