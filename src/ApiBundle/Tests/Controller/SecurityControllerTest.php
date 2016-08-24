<?php

namespace ApiBundle\Tests\Controller;

class SecurityControllerTest extends ControllerTestCase
{
    protected $apiKeyAuthentication = true;

    public function testAuthenticationWithInvalidCredentialsFails()
    {
        $client = $this->getClient();

        $client->request('POST', '/api/v1/auth/logins', ['phone' => '71111111111', 'password' => 'test']);

        $response = $client->getResponse();
        $this->assert404($response);
        $this->assertException($response, 404);
    }

    public function testAuthenticationWithValidCredentials()
    {
        $client = $this->getClient();

        $client->request('POST', '/api/v1/auth/logins', ['phone' => '79999999999', 'password' => 'demo']);

        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $content = $this->getResponseContent($response);
        $this->assertArrayHasKey('token', $content['data']);
    }
}
