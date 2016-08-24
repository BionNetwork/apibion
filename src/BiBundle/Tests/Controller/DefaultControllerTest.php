<?php

namespace BiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testUserIsRedirectedToLoginPage()
    {
        $client = static::createClient();

        $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isRedirection(), "user is not redirected");

        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'), "user is not redirected to /login page");
    }

    public function testLoginPageContainsValidFields()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Войти")')->count());
        $this->assertGreaterThan(0, $crawler->filter('input[name="login[username]"]')->count());
        $this->assertGreaterThan(0, $crawler->filter('input[name="login[username]"]')->count());
        $this->assertGreaterThan(0, $crawler->filter('input[name="login[password]"]')->count());
    }
}
