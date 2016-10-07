<?php


namespace BiBundle\Tests\Controller;


use BiBundle\Entity\UserRole;

class UserRoleControllerTest extends ControllerTestCase
{
    public function testIndexAction()
    {
        $this->client->request('GET', '/user/role/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testNewAction()
    {
        $this->client->request('GET', '/user/role/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testShowAction()
    {
        /** @var UserRole $role */
        $role = $this->container->get('doctrine.orm.entity_manager')->getRepository(UserRole::class)->findOneBy([]);
        $this->client->request('GET', "/user/role/{$role->getId()}/show");
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testEditAction()
    {
        /** @var UserRole $role */
        $role = $this->container->get('doctrine.orm.entity_manager')->getRepository(UserRole::class)->findOneBy([]);
        $this->client->request('GET', "/user/role/{$role->getId()}/edit");
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
