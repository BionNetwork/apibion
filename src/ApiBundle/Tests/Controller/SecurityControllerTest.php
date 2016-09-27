<?php

namespace ApiBundle\Tests\Controller;

class SecurityControllerTest extends ControllerTestCase
{
    protected $apiKeyAuthentication = true;

    public function testAuthenticationWithInvalidCredentialsFails()
    {
        $client = $this->getClient();

        $client->request('POST', '/api/v1/auth/logins', ['login' => 'user', 'password' => 'user1']);
        $response = $client->getResponse();
        $this->assert401($response);
        $this->assertException($response, 401);
    }

    public function testAuthenticationWithValidCredentials()
    {
        $client = $this->getClient();

        $client->request('POST', '/api/v1/auth/logins', ['login' => 'user', 'password' => 'user']);
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $content = $this->getResponseContent($response);
        $this->assertArrayHasKey('token', $content['data']);
    }
}
