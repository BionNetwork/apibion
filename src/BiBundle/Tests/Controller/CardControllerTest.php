<?php


namespace BiBundle\Tests\Controller;


use BiBundle\Entity\Card;

class CardControllerTest extends ControllerTestCase
{
    public function testIndexAction()
    {
        $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testNewAction()
    {
        $this->client->request('GET', '/card/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testEditAction()
    {
        /** @var Card $card */
        $card = $this->container->get('repository.card_repository')->findOneBy([]);
        $this->client->request('GET', "/card/{$card->getId()}/edit");
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
