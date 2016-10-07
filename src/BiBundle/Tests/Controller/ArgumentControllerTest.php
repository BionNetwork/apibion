<?php


namespace BiBundle\Tests\Controller;


use BiBundle\Entity\Card;

class ArgumentControllerTest extends ControllerTestCase
{
    public function testIndexAction()
    {
        $this->client->request('GET', '/argument/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testEditAction()
    {
        /** @var Card $argument */
        $argument = $this->container->get('repository.argument_repository')->findOneBy([]);
        $this->client->request('GET', "/argument/{$argument->getId()}/edit");
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
