<?php

namespace BiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

class ControllerTestCase extends WebTestCase
{
    const TEST_USER_LOGIN = 'user';
    const TEST_USER_PASSWORD = 'user';

    /** @var  Container */
    protected $container;
    /** @var  Client */
    protected $client;

    protected function setUp()
    {
        static::bootKernel();
        $this->container = static::$kernel->getContainer();
        $this->client = self::createClient([],
            [
                'PHP_AUTH_USER' => static::TEST_USER_LOGIN,
                'PHP_AUTH_PW' => static::TEST_USER_PASSWORD,
            ]
        );
    }

    protected static function createClient(array $options = [], array $server = [])
    {
        /** @var Client $client */
        $client = static::$kernel->getContainer()->get('test.client');
        $client->setServerParameters($server);

        return $client;
    }
}
