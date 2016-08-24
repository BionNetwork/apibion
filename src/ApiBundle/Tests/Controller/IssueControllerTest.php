<?php
/**
 * @package    ApiBundle\Tests\Controller;
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */
namespace ApiBundle\Tests\Controller;

class IssueControllerTest extends ControllerTestCase
{
    protected $apiTokenAuthentication = true;

    public static function tearDownAfterClass()
    {
        static::$em->createQuery("DELETE FROM BiBundle\Entity\IssueStatusStats s")->execute();
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
     * @param int $projectId
     * @return array
     */
    public function testIssueCanBeCreated($projectId)
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
        return ['id' => $content['data']['id'], 'project_id' => $projectId];
    }

    /**
     * @depends testIssueCanBeCreated
     * @param array $data
     * @return array
     */
    public function testIssueCanBeCreatedWithParent(array $data)
    {
        $projectId = $data['project_id'];
        $issueId = $data['id'];
        $client = $this->getClient();
        $client->request(
            'POST',
            "/api/v1/projects/{$projectId}/issues",
            [
                'name' => 'test issue',
                'parent' => $issueId,
            ]);
        $response = $client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $content = $this->getResponseContent($response);
        $this->assertArrayHasKey('id', $content['data']);
        return ['id' => $content['data']['id'], 'parent' => $issueId];
    }

    /**
     * @depends testProjectCanBeCreated
     * @param $projectId
     */
    public function testIssueCannotBeCreatedWithoutName($projectId)
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            "/api/v1/projects/{$projectId}/issues", []);
        $response = $client->getResponse();

        $this->assertFalse($response->isSuccessful());
        $content = $this->getResponseContent($response);
        $this->assertException($response, 400);
        $this->assertNotEmpty($content['errors']['issue']['children']);
        $this->assertArrayHasKey('name', $content['errors']['issue']['children']);
    }

    /**
     * @depends testIssueCanBeCreated
     *
     * @param array $data
     */
    public function testIssueCanBeUpdated(array $data)
    {
        $projectId = $data['project_id'];
        $issueId = $data['id'];

        $client = $this->getClient();
        $client->request(
            'PUT',
            "/api/v1/issues/{$issueId}",
            [
                'name' => 'test issue',
                'project' => $projectId,
                'due_date' => '01.01.2016',
                'start_date' => '01.01.2016',
                'assigned_to' => $this->getUser()->getId(),
            ]);
        $response = $client->getResponse();
        $this->assert204($response);
    }

    /**
     * @depends testIssueCanBeCreated
     * @param array $issue
     */
    public function testIssueCanBeFound($issue)
    {
        $client = $this->getClient();
        $client->request(
            'GET',
            "/api/v1/projects/{$issue['project_id']}/issues?id={$issue['id']}",
            []);
        $response = $client->getResponse();
        $content = $this->getResponseContent($response);
        $this->assertNotEmpty($content['data'][0]);
        $this->assertArrayHasKey('id', $content['data'][0]);
    }

    /**
     * @depends testIssueCanBeCreated
     *
     * @param array $data
     */
    public function testIssueCanNotBeUpdatedWithInvalidStartedOnDate(array $data)
    {
        $projectId = $data['project_id'];
        $issueId = $data['id'];

        $futureDate = (new \DateTime())->modify('+1 day');
        $client = $this->getClient();
        $client->request(
            'PUT',
            "/api/v1/issues/{$issueId}",
            [
                'name' => 'test issue',
                'project' => $projectId,
                'due_date' => '01.01.2016',
                'start_date' => '01.01.2016',
                'assigned_to' => $this->getUser()->getId(),
                'started_on' => $futureDate->format('d.m.Y')
            ]);
        $response = $client->getResponse();
        $this->assert400($response);
    }

    /**
     * @depends testIssueCanBeCreated
     *
     * @param array $data
     */
    public function testIssueCanNotBeUpdatedWithInvalidClosedOnDate(array $data)
    {
        $projectId = $data['project_id'];
        $issueId = $data['id'];

        $futureDate = (new \DateTime())->modify('+1 day');
        $client = $this->getClient();
        $client->request(
            'PUT',
            "/api/v1/issues/{$issueId}",
            [
                'name' => 'test issue',
                'project' => $projectId,
                'due_date' => '01.01.2016',
                'start_date' => '01.01.2016',
                'assigned_to' => $this->getUser()->getId(),
                'closed_on' => $futureDate->format('d.m.Y')
            ]);
        $response = $client->getResponse();
        $this->assert400($response);
    }

    /**
     * @depends testIssueCanBeCreated
     *
     * @param array $data
     */
    public function testIssueCanNotBeUpdatedWithClosedOnDateEarlierStartedOnDate(array $data)
    {
        $projectId = $data['project_id'];
        $issueId = $data['id'];

        $client = $this->getClient();
        $client->request(
            'PUT',
            "/api/v1/issues/{$issueId}",
            [
                'name' => 'test issue',
                'project' => $projectId,
                'due_date' => '01.01.2016',
                'start_date' => '01.01.2016',
                'assigned_to' => $this->getUser()->getId(),
                'started_on' => '10.10.2016',
                'closed_on' => '01.01.2016'
            ]);
        $response = $client->getResponse();
        $this->assert400($response);
    }

    /**
     * @depends testIssueCanBeCreated
     *
     * @param array $data
     * @return array
     */
    public function testIssueCanBeTakenInWork(array $data)
    {
        $issueId = $data['id'];

        $client = $this->getClient();
        $client->request(
            'PUT',
            "/api/v1/issues/{$issueId}/inwork",
            [
                'started_on' => (new \DateTime())->format('d.m.Y')
            ]);

        $response = $client->getResponse();
        $this->assert204($response);
        return $data;
    }

    /**
     * @depends testIssueCanTakenBeInWork
     *
     * @param $issue
     */
    public function testIssueInworkStatusSetCorrectly($issue)
    {

        $client = $this->getClient();
        $client->request(
            'GET',
            "/api/v1/projects/{$issue['project_id']}/issues?id={$issue['id']}",
            []);
        $response = $client->getResponse();
        $content = $this->getResponseContent($response);
        $this->assertNotEmpty($content['data']);
        $this->assertTrue($content['data'][0]['status'] == 'in_progress');
    }
    /**
     * @depends testIssueCanBeCreated
     *
     * @param array $issue
     *
     */
    public function testIssueHasInProgressDefaultStatus($issue)
    {
        $client = $this->getClient();
        $client->request(
            'GET',
            "/api/v1/projects/{$issue['project_id']}/issues?id={$issue['id']}",
            []);
        $response = $client->getResponse();
        $content = $this->getResponseContent($response);
        $this->assertNotEmpty($content['data']);
        $this->assertTrue($content['data'][0]['status'] == 'in_progress');
    }

    /**
     * @depends testIssueCanBeCreated
     * @param $issue
     */
    public function testIssueCanBeClosed($issue)
    {
        $this->assertInternalType('array', $issue);
        $client = $this->getClient();
        $client->request('PUT', "/api/v1/issues/{$issue['id']}/close");
        $response = $client->getResponse();
        $this->assert204($response);
    }

    /**
     * @depends testIssueCanBeCreated
     *
     * @param array $issue
     *
     */
    public function testIssueIsClosed($issue)
    {
        $client = $this->getClient();
        $client->request(
            'GET',
            "/api/v1/projects/{$issue['project_id']}/issues?id={$issue['id']}",
            []);
        $response = $client->getResponse();
        $content = $this->getResponseContent($response);
        $this->assertNotEmpty($content['data'][0]);
        $this->assertTrue($content['data'][0]['status'] == 'done');
    }

    /**
     * @depends testIssueCanBeCreated
     * @param $issue
     */
    public function testIssueCanBeDeleted($issue)
    {
        $this->assertInternalType('array', $issue);
        $client = $this->getClient();
        $client->request('DELETE', "/api/v1/issues/{$issue['id']}");
        $response = $client->getResponse();
        $this->assert204($response);
    }

    /**
     * @depends testIssueCanBeCreatedWithParent
     * @param array $issue
     */
    public function testIssueWithParentCanBeDeleted($issue)
    {
        $this->assertInternalType('array', $issue);
        $client = $this->getClient();
        $client->request('DELETE', "/api/v1/issues/{$issue['id']}");
        $response = $client->getResponse();
        $this->assert204($response);
    }
}