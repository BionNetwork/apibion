<?php
/**
 * @package    ApiBundle\Tests\Controller;
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */
namespace ApiBundle\Tests\Controller;

class OrganizationControllerTest extends ControllerTestCase
{
    protected $apiTokenAuthentication = true;
    /**
     * @var
     */
    protected static $organizations;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        static::$organizations = static::$em->createQuery("SELECT o FROM BiBundle\Entity\Organization o")
            ->execute();
        foreach (static::$organizations as $organization) {
            static::$organizations[] = $organization->getId();
        }
    }

    public static function tearDownAfterClass()
    {
        static::$em->createQuery("DELETE FROM BiBundle\Entity\Organization o WHERE o.id NOT IN (:id)")
            ->setParameter('id', static::$organizations)->execute();
        static::$em->close();
    }

    public function testOrganizationCanBeCreated()
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            '/api/v1/organizations',
            [
                'name' => 'new test organization name',
                'full_name' => 'new test organization fullname',
                'itn' => rand(1111111111, 9999999999),
            ]);
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $content = $this->getResponseContent($response);
        $this->assertArrayHasKey('id', $content['data']);
        return $content['data']['id'];
    }

    public function testOrganizationCreateThrowsErrorWithInvalidData()
    {
        $client = $this->getClient();
        $client->request('POST', '/api/v1/organizations');
        $response = $client->getResponse();
        $this->assert400($response);
    }

    /**
     * @depends testOrganizationCanBeCreated
     */
    public function testOrganizationCanBeDeleted($id)
    {
        $this->assertInternalType('int', $id);
        $client = $this->getClient();
        $client->request('DELETE', "/api/v1/organizations/{$id}");
        $response = $client->getResponse();
        $this->assert204($response);
    }

    /**
     * @depends testOrganizationCanBeCreated
     */
    public function testOrganizationDeleteThrowsErrorWithInvalidIdentifier()
    {
        $client = $this->getClient();
        $client->request('DELETE', "/api/v1/organizations/notCorrectValue");
        $response = $client->getResponse();
        $this->assert404($response);
    }

    /**
     * @depends testOrganizationCanBeCreated
     */
    public function testOrganizationCanBeUpdatedInBatchMode($id)
    {
        $client = $this->getClient();
        $client->request('PATCH', "/api/v1/organization?id[]={$id}&id[]={$id}");
        $response = $client->getResponse();
        $this->assert204($response);
    }

    /**
     * @depends testOrganizationCanBeCreated
     */
    public function testOrganizationCanBeMovedToArchive($id)
    {
        $client = $this->getClient();
        $client->request('PATCH', "/api/v1/organizations/{$id}/archive");
        $response = $client->getResponse();
        $this->assert204($response);
    }

    /**
     * @depends testOrganizationCanBeCreated
     */
    public function testPutOrganizationCanBeUpdated($id)
    {
        $client = $this->getClient();
        $client->request('PUT', "/api/v1/organizations/{$id}",[
            'name' => 'updated test organization name',
            'full_name' => 'updated test organization fullname',
            'itn' => rand(1111111111, 9999999999),
        ]);
        $response = $client->getResponse();
        $this->assert204($response);
    }
}