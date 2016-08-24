<?php

namespace BiBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use BiBundle\Entity\Issue;
use BiBundle\Entity\IssueMemberRole;
use BiBundle\Entity\IssueStatus;
use BiBundle\Entity\IssueWorkflowStatus;
use BiBundle\Entity\Project;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\ProjectStatus;
use BiBundle\Entity\ProjectWorkflowStatus;
use BiBundle\Entity\User;

class IssueServiceTest extends KernelTestCase implements ContainerAwareInterface
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Возвращает юзера
     *
     * @param string $login
     * @return User
     */
    protected function getDemoUser($login = 'demo')
    {
        return $this->em->getRepository('BiBundle:User')->findOneBy(['login' => $login]);
    }

    protected function setUp()
    {
        self::bootKernel();
        $this->setContainer(static::$kernel->getContainer());
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')->getManager();

        $user = $this->getDemoUser();
        $service = $this->getMockBuilder('\BiBundle\Service\Project')
            ->setMethods(['getUser', 'getEntityManager', 'getNotificationManager'])
            ->disableOriginalConstructor()
            ->getMock();

        $notificationManager = $this->getMockBuilder('\BiBundle\Event\NotificationManager')
            ->setMethods(['notify'])
            ->disableOriginalConstructor()
            ->getMock();
        $notificationManager->expects($this->any())->method('notify')->will($this->returnValue(true));
        $this->container->set('event.notification_manager', $notificationManager);

        $service->expects($this->any())->method('getUser')->will($this->returnValue($user));
        $service->expects($this->any())->method('getEntityManager')->will($this->returnValue($this->em));
        $service->expects($this->any())->method('getNotificationManager')->will($this->returnValue($notificationManager));
        $this->container->set('bi.project.service', $service);

        $service = $this->getMockBuilder('\BiBundle\Service\Issue')
            ->setMethods(['getUser', 'getEntityManager'])
            ->disableOriginalConstructor()
            ->getMock();
        $service->expects($this->any())->method('getUser')->will($this->returnValue($user));
        $service->expects($this->any())->method('getEntityManager')->will($this->returnValue($this->em));
        $this->container->set('bi.issue.service', $service);
    }


    /**
     * Create project test
     *
     * @return Project
     */
    public function testCreateProject()
    {
        $user = $this->getDemoUser();
        $status = $this->container->get('repository.project_status_repository')->findOneBy(['code' => ProjectStatus::STATUS_ACTIVE]);
        $workflowStatus = $this->container->get('repository.project_workflow_status_repository')->findOneBy(['code' => ProjectWorkflowStatus::STATUS_NEW]);

        $service = $this->container->get('bi.project.service');

        $project = new Project();
        $project->setAuthor($user);
        $project->setAssignedTo($user);
        $project->setStatus($status);
        $project->setWorkflowStatus($workflowStatus);
        $project->setName('new project');
        $project->setFullname('new fullname');
        $project->setDescription('new description');
        $project->setStartDate(new \DateTime());
        $project->setDueDate(new \DateTime());
        $project->setCustomer($user);
        $service->save($project);

        $this->assertNotNull($project->getId());
        return $project;
    }

    /**
     * Project accept status
     * @throws \BiBundle\Entity\Exception\ValidatorException
     *
     * @return Issue
     */
    public function testIssueCreateSuccess()
    {
        $project = $this->testCreateProject();
        $user = $this->getDemoUser();
        $service = $this->container->get('bi.issue.service');

        $issue = new Issue();
        $issue->setProject($project);
        $issue->setAuthor($user);
        $issue->setName('test issue');
        $issue->setDescription('test description');

        $service->save($issue);
        $condition = $issue->getId() != 0;
        $this->assertTrue($condition);
        return $issue;
    }

    /**
     * @depends testIssueCreateSuccess
     *
     * @expectedException \BiBundle\Service\Exception\Issue\InvalidDateException
     */
    public function testIssueSaveWithInvalidStartedOnDate(Issue $issue)
    {
        $futureDate = (new \DateTime())->modify('+1 day');
        $issue->setStartedOn($futureDate);
        $service = $this->container->get('bi.issue.service');
        $service->save($issue);
    }

    /**
     * @depends testIssueCreateSuccess
     *
     * @expectedException \BiBundle\Service\Exception\Issue\InvalidDateException
     */
    public function testIssueSaveWithInvalidClosedOnDate(Issue $issue)
    {
        $futureDate = (new \DateTime())->modify('+1 day');
        $issue->setClosedOn($futureDate);
        $service = $this->container->get('bi.issue.service');
        $service->save($issue);
    }

    /**
     * @depends testIssueCreateSuccess
     *
     * @expectedException \BiBundle\Service\Exception\Issue\InvalidDateException
     */
    public function testIssueSaveWithClosedOnDateEarlierStartedOnDate(Issue $issue)
    {
        $nowDate = new \DateTime();
        $futureDate = (new \DateTime())->modify('+1 day');
        $issue->setStartedOn($futureDate);
        $issue->setClosedOn($nowDate);
        $service = $this->container->get('bi.issue.service');
        $service->save($issue);
    }

    public function testIssueCanBeTakenInWorkSuccess()
    {
        $issue = $this->testIssueCreateSuccess();
        $service = $this->container->get('bi.issue.service');
        $service->takeInWork($issue, new \DateTime());
        $this->assertTrue($issue->getWorkflowStatus()->getCode() == IssueWorkflowStatus::STATUS_IN_PROGRESS);
    }

    public function testIssueCanBeClosedSuccess()
    {
        $issue = $this->testIssueCreateSuccess();
        $service = $this->container->get('bi.issue.service');
        $service->close($issue, new \DateTime());
        $this->assertTrue($issue->getWorkflowStatus()->getCode() == IssueWorkflowStatus::STATUS_DONE);
    }

    public function originatorDataProvider()
    {
        return [
            $this->getDemoUser(),
            $this->getDemoUser('rose')
        ];
    }

    public function testOriginatorCanBeAddedToIssue()
    {
        foreach ($this->originatorDataProvider() as $user) {
            $issue = $this->testIssueCreateSuccess();
            $service = $this->container->get('bi.issue.service');
            $member = $service->setOriginator($issue, $user);
            $memberRepo = $this->em->getRepository('BiBundle:IssueMember');
            $memberRoleRepo = $this->em->getRepository('BiBundle:IssueMemberRole');
            $role = $memberRoleRepo->findOneBy(['code' => IssueMemberRole::ROLE_ORIGINATOR]);

            $this->assertNotNull($member->getId());
            $foundMember = $memberRepo->findMember($issue->getId(), $role->getId(), $user->getId());
            $this->assertInstanceOf('\BiBundle\Entity\IssueMember', $foundMember);
            $this->assertEquals($member->getId(), $foundMember->getId());
        }
    }

    protected function tearDown()
    {
        if (!$this->hasDependencies()) {
            $q = $this->em->createQuery("DELETE FROM BiBundle\Entity\IssueMember m");
            $q->execute();
            $q = $this->em->createQuery("DELETE FROM BiBundle\Entity\Issue m");
            $q->execute();
            $q = $this->em->createQuery("DELETE FROM BiBundle\Entity\Member m");
            $q->execute();
            $q = $this->em->createQuery("DELETE FROM BiBundle\Entity\Project p");
            $q->execute();
            $this->em->close();
            parent::tearDown();
        }
    }
}
