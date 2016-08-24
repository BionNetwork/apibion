<?php
/**
 * @package    ApiBundle\Tests\Controller;
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */
namespace ApiBundle\Tests\Controller;

use Symfony\Component\HttpKernel\Exception\HttpException;
use BiBundle\Entity\ProjectStatus;

class ProjectControllerTest extends ControllerTestCase
{
    protected $apiTokenAuthentication = true;

    public static function tearDownAfterClass()
    {
        static::$em->createQuery("DELETE FROM BiBundle\Entity\Issue i")->execute();
        static::$em->createQuery("DELETE FROM BiBundle\Entity\Member m")->execute();
        static::$em->createQuery("DELETE FROM BiBundle\Entity\Project p")->execute();
        static::$em->close();
    }

    public function testProjectCanBeCreated()
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            '/api/v1/projects',
            [
                'name' => 'test project',
                'fullname' => 'my new project',
                'assigned_to' => $this->getUser()->getId(),
                'due_date' => date("d.m.Y")
            ]);
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $content = $this->getResponseContent($response);
        $this->assertArrayHasKey('id', $content['data']);
        return $content['data']['id'];
    }

    /**
     * @depends testProjectCanBeCreated
     *
     * @param $projectId
     *
     * @return int
     */
    public function testProjectCanCreateIssue($projectId)
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            "/api/v1/projects/{$projectId}/issues",
            [
                'name' => 'test issue'
            ]);
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $content = $this->getResponseContent($response);
        $this->assertArrayHasKey('id', $content['data']);
        return $content['data']['id'];
    }

    public function invalidDataProvider()
    {
        return [
            [
                [
                    'data' => [
                        'name' => 'test',
                        'description' => 'error'
                    ],
                    'errors' => [
                        'assigned_to',
                        'due_date'
                    ]
                ]
            ],
            [
                [
                    'data' => [
                        'name' => 'test',
                        'description' => 'some',
                        'assigned_to' => 99999,
                        'due_date' => date("d.m.Y")
                    ],
                    'errors' => [
                        'assigned_to'
                    ]

                ]
            ],
            [
                [
                    'data' => [
                        'name' => 'test',
                        'description' => 'some',
                        'assigned_to' => 1,
                        'due_date' => false
                    ],
                    'errors' => [
                        'due_date'
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param array $data
     */
    public function testProjectCreateThrowsErrorWithInvalidData(array $data)
    {
        $client = $this->getClient();
        $client->request('POST', '/api/v1/projects', $data['data']);
        $response = $client->getResponse();
        $this->assert400($response);

        $response = $this->getResponseContent($response);
        $this->assertNotEmpty($response['errors'], "Errors are not returned");
        $this->assertArrayHasKey('project', $response['errors'], "Project errors should contain error key 'project'");
        $this->assertArrayHasKey('children', $response['errors']['project'], "Project form should contain children errors");
        $children = $response['errors']['project']['children'];
        foreach ($data['errors'] as $error) {
            $this->assertArrayHasKey($error, $children, sprintf("Key <%s> should be returned on error", $error));
        }
    }

    /**
     * @depends testProjectCanBeCreated
     * @param int $id
     */
    public function testProjectCanBeDeleted($id)
    {
        $this->assertInternalType('int', $id);
        $client = $this->getClient();
        $client->request('DELETE', "/api/v1/projects/{$id}");
        $response = $client->getResponse();
        $this->assert204($response);
    }

    /**
     * @depends testProjectCanBeCreated
     * @param int $id
     */
    public function testProjectDeleteThrowsErrorWithInvalidIdentifier($id)
    {
        $client = $this->getClient();
        $client->request('DELETE', "/api/v1/organizations/notCorrectValue");
        $response = $client->getResponse();
        $this->assert404($response);
    }

    /**
     * @depends testProjectCanBeCreated
     * @param int $id
     *
     * @return int
     */
    public function testProjectCanBeUpdated($id)
    {
        $client = $this->getClient();
        $client->request('PUT', "/api/v1/projects/{$id}", [
            'name' => 'updated test project name',
            'fullname' => 'my new project',
            'assigned_to' => $this->getUser()->getId(),
            'due_date' => date("d.m.Y")
        ]);
        $response = $client->getResponse();
        $this->assert204($response);
        return $id;
    }
    /**
     * @depends testProjectCanBeCreated
     * @param int $id
     *
     */
    public function testProjectCustomerCanNotBeSetInUpdate($id)
    {
        $client = $this->getClient();
        $client->request('PUT', "/api/v1/projects/{$id}", [
            'name' => 'updated test project name',
            'fullname' => 'my new project',
            'assigned_to' => $this->getUser()->getId(),
            'due_date' => date("d.m.Y"),
            'customer' => $this->getUser()->getId(),
        ]);
        $response = $client->getResponse();
        $this->assert400($response);
    }

    /**
     * @depends testProjectCanBeUpdated
     * @param int $id
     */
    public function testProjectCanBeAccepted($id)
    {
        $client = $this->getClient();
        $client->request(
            'PATCH',
            "/api/v1/projects/{$id}/accept",
            [
                'customer_id' => $this->getUser()->getId(),
            ]);
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @depends testProjectCanBeUpdated
     * @param int $id
     */
    public function testProjectCanBeDeny($id)
    {
        $client = $this->getClient();
        $client->request(
            'PATCH',
            "/api/v1/projects/{$id}/deny",
            [
                'customer_id' => $this->getUser()->getId(),
                'comment' => 'test comment',
            ]);
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @depends testProjectCanBeCreated
     * @depends testProjectCanCreateIssue
     *
     * @param $projectId
     */
    public function testProjectCanFindIssue($projectId)
    {
        $client = $this->getClient();
        $client->request(
            'GET',
            "/api/v1/projects/{$projectId}/issues",
            []);
        $response = $client->getResponse();
        $content = $this->getResponseContent($response);
        $this->assertNotEmpty($content['data'][0]);
        $this->assertArrayHasKey('id', $content['data'][0]);
    }

    /**
     * @depends testProjectCanBeCreated
     *
     * @param $projectId
     * @return array
     */
    public function testUserCanBeAddedToTeam($projectId)
    {
        $client = $this->getClient();
        $userId = $this->getUser()->getId();
        $client->request(
            'POST',
            "/api/v1/projects/{$projectId}/teams/{$userId}",
            []);
        $response = $client->getResponse();
        $this->assert204($response);
        return ['project' => $projectId, 'user' => $userId];
    }

    /**
     *
     * @depends testProjectCanBeCreated
     * @param $projectId
     * @return array
     */
    public function testUserThatIsNotMemberCanNotBeAddedToTeam($projectId)
    {
        $client = $this->getClient();
        $userId = $this->getUser('rose')->getId();
        $client->request(
            'POST',
            "/api/v1/projects/{$projectId}/teams/{$userId}",
            []);
        $response = $client->getResponse();

        $this->assert400($response);
        $response = $this->getResponseContent($response);
        $this->assertNotEmpty($response['exception'], "Errors are not returned");
        return ['project' => $projectId, 'user' => $userId];
    }

    public function testAddUserToTeamWithInvalidProject()
    {
        $client = $this->getClient();
        $userId = $this->getUser('rose')->getId();
        $projectId = 10000;
        $client->request(
            'POST',
            "/api/v1/projects/{$projectId}/teams/{$userId}",
            []);
        $response = $client->getResponse();
        $this->assert404($response);
        $response = $this->getResponseContent($response);
        $this->assertNotEmpty($response['exception'], "Errors are not returned");
    }

    /**
     * @depends testUserCanBeAddedToTeam
     *
     * @param array $data
     */
    public function testUserCanBeRemovedFromTeam(array $data)
    {
        $this->assertArrayHasKey('project', $data);
        $this->assertArrayHasKey('user', $data);

        $projectId = $data['project'];
        $userId = $data['user'];
        $client = $this->getClient();

        $client->request(
            'DELETE',
            "/api/v1/projects/{$projectId}/teams/{$userId}",
            []);
        $response = $client->getResponse();
        $this->assert204($response);
    }

    /**
     * @depends testUserThatIsNotMemberCanNotBeAddedToTeam
     *
     * @param array $data
     */
    public function testUserThatIsNotMemberCanNotBeRemovedFromTeam(array $data)
    {
        $this->assertArrayHasKey('project', $data);
        $this->assertArrayHasKey('user', $data);

        $projectId = $data['project'];
        $userId = $data['user'];
        $client = $this->getClient();

        $client->request(
            'DELETE',
            "/api/v1/projects/{$projectId}/teams/{$userId}",
            []);
        $response = $client->getResponse();

        $this->assert400($response);
        $response = $this->getResponseContent($response);
        $this->assertNotEmpty($response['exception'], "Errors are not returned");
    }

    /**
     * @depends testProjectCanBeCreated
     *
     * @param $projectId
     */
    public function testCustomerCanBeAdded($projectId)
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            "/api/v1/projects/{$projectId}/members/customers",
            ['user' => $this->getUser()->getId()]);
        $response = $client->getResponse();
        $this->assert204($response);
    }
    /**
     * @depends testCustomerCanBeAdded
     *
     * @param $projectId
     */
    public function testCustomerCanBeDeleted($projectId)
    {
        $project = static::$em->find('BiBundle:Project', $projectId);
        $client = $this->getClient();
        $client->request(
            'DELETE',
            "/api/v1/projects/{$projectId}/members/customers",
            ['user' => $project->getCustomer()->getId()]);
        $response = $client->getResponse();
        $this->assert204($response);
    }


    /**
     * @depends testProjectCanBeCreated
     *
     * @param int $projectId
     *
     * @return mixed
     */
    public function testWatcherCanBeAdded($projectId)
    {
        $client = $this->getClient();
        $userId = $this->getUser('rose')->getId();
        $client->request(
            'POST',
            "/api/v1/projects/{$projectId}/members/watchers",
            [
                'user' => $userId,
            ]);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $response = $this->getResponseContent($response);
        $this->assertNotEmpty($response['data']['id']);
        return ['project' => $projectId, 'user' => $userId];
    }

    /**
     * @depends testWatcherCanBeAdded
     *
     * @param $data
     */
    public function testWatcherCanBeRemoved($data)
    {
        $this->assertArrayHasKey('project', $data);
        $this->assertArrayHasKey('user', $data);

        $projectId = $data['project'];
        $userId = $data['user'];
        $client = $this->getClient();

        $client->request(
            'DELETE',
            "/api/v1/projects/{$projectId}/members/watchers",
            [
                'user' => $userId,
            ]);
        $response = $client->getResponse();
        $this->assert204($response);
    }
}