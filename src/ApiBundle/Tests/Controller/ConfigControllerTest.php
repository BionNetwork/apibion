<?php


namespace ApiBundle\Tests\Controller;


class ConfigControllerTest extends ControllerTestCase
{
    protected $apiTokenAuthentication = true;

    public function testGetConfigStringsEn()
    {
        $this->client->request('GET', '/api/v1/config/strings', [], [], ['HTTP_Accept-Language' => 'en']);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testGetConfigStringsRu()
    {
        $this->client->request('GET', '/api/v1/config/strings', [], [], ['HTTP_Accept-Language' => 'ru']);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}
