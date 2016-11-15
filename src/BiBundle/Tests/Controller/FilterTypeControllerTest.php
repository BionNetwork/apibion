<?php


namespace BiBundle\Tests\Controller;


class FilterTypeControllerTest extends ControllerTestCase
{
    public function testIndexAction()
    {
        $this->client->request('GET', '/filters/types/');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testNewAction()
    {
        $this->client->request('GET', '/filters/types/new');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
