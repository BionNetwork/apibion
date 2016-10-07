<?php


namespace BiBundle\Tests\Controller;


class FilterControlTypeControllerTest extends ControllerTestCase
{
    public function testIndexAction()
    {
        $this->client->request('GET', '/filter-control-type/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testNewAction()
    {
        $this->client->request('GET', '/filter-control-type/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
