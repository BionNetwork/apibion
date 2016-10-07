<?php


namespace BiBundle\Tests\Controller;


use BiBundle\Entity\User;

class UserControllerTest extends ControllerTestCase
{
    public function testIndexAction()
    {
        $this->client->request('GET', '/user/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testNewAction()
    {
        $this->client->request('GET', '/user/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testShowAction()
    {
        /** @var User $user */
        $user = $this->container->get('repository.user_repository')->findOneBy([]);
        $this->client->request('GET', "/user/{$user->getId()}/show");
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testEditAction()
    {
        /** @var User $user */
        $user = $this->container->get('repository.user_repository')->findOneBy([]);
        $this->client->request('GET', "/user/{$user->getId()}/edit");
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
