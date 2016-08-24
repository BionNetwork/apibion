<?php

namespace BiBundle\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use BiBundle\Entity\Member;
use BiBundle\Entity\MemberRole;
use BiBundle\Entity\Project;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\ProjectStatus;
use BiBundle\Entity\ProjectWorkflowStatus;

class ProjectServiceTest extends KernelTestCase implements ContainerAwareInterface
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @return \BiBundle\Service\Project
     */
    public function getService()
    {
        return $this->container->get("bi.project.service");
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Возвращает юзера
     *
     * @return \BiBundle\Entity\User
     */
    protected function getDemoUser()
    {
        return $this->em->getRepository('BiBundle:User')->findOneBy(['login' => 'demo']);
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
    }

    /**
     * Create project test
     *
     * @return Project
     */
    public function testCreateProject()
    {
        $user = $this->getDemoUser();
        $status = $this->em->getRepository('BiBundle:ProjectStatus')->findOneBy(['code' => ProjectStatus::STATUS_ACTIVE]);
        $workflowStatus = $this->em->getRepository('BiBundle:ProjectWorkflowStatus')->findOneBy(['code' => ProjectWorkflowStatus::STATUS_NEW]);

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

        $this->getService()->save($project);

        $this->assertNotNull($project->getId());
        $this->assertEquals(ProjectStatus::STATUS_ACTIVE, $project->getStatus()->getCode());
        // assignee and author are the same user
        $this->assertEquals(ProjectWorkflowStatus::STATUS_PENDING, $project->getWorkflowStatus()->getCode());
        return $project;
    }

    /**
     * Project accept status
     */
    public function testProjectAcceptSuccess()
    {
        $project = $this->testCreateProject();
        $projectService = $this->container->get('bi.project.service');
        $projectService->accept($project, $project->getCustomer()->getId());

        $condition1 = $project->getStatus()->getCode() == ProjectStatus::STATUS_ARCHIVE;
        $condition2 = $project->getWorkflowStatus()->getCode() == ProjectWorkflowStatus::STATUS_CLOSED;

        $this->assertTrue($condition1 && $condition2);
    }

    /**
     * Project can be denied
     */
    public function testProjectDenySuccess()
    {
        $project = $this->testCreateProject();
        $projectService = $this->container->get('bi.project.service');
        $projectService->deny($project, $project->getCustomer()->getId());

        $condition1 = $project->getWorkflowStatus()->getCode() == ProjectWorkflowStatus::STATUS_IN_PROGRESS;
        $condition2 = $project->getClosedOn() == null;

        $this->assertTrue($condition1 && $condition2);
    }

    public function testActivateAssignee()
    {
        $project = $this->testCreateProject();
        $repo = $this->em->getRepository('BiBundle:Member');
        $members = $repo->getMembers($project->getId());
        $this->assertNotEmpty($members);
        $this->assertCount(1, $members);
        /** @var Member $member */
        $member = array_shift($members);
        $this->assertNotNull($member, "project does not have assignee");
        $this->assertEquals(MemberRole::ROLE_ASSIGNEE, $member->getRole()->getCode());
        $this->assertFalse($member->getActive(), "Assignee should be not active at first");
        $projectService = $this->container->get('bi.project.service');
        $projectService->activateAssignee($project, $project->getAssignedTo());

        $members = $repo->getMembers($project->getId());
        /** @var Member $member */
        $member = array_shift($members);
        $this->assertTrue($member->getActive(), "Assignee should be active now");
    }

    /**
     * @return array
     */
    public function testAddWatcher()
    {
        $project = $this->testCreateProject();
        $user = $this->em->getRepository('BiBundle:User')->findOneBy(['login' => 'rose']);

        $projectService = $this->container->get('bi.project.service');
        $projectService->addWatcher($project, $user);
        $repo = $this->em->getRepository('BiBundle:Member');
        $members = $repo->getMembers($project->getId());
        $this->assertNotEmpty($members);
        $this->assertCount(2, $members);

        $member = null;
        foreach ($members as $currentMember) {
            if ($currentMember->getRole()->getCode() == MemberRole::ROLE_WATCHER) {
                $member = $currentMember;
                break;
            }
        }
        $this->assertNotNull($member, "watcher is not added to project");
        return ['project' => $project, 'member' => $member];
    }

    public function testChangeMemberRole()
    {
        $data = $this->testAddWatcher();

        foreach (['project', 'member'] as $key) {
            $this->assertArrayHasKey($key, $data);
        }
        /** @var Project $project */
        $project = $data['project'];
        /** @var Member $member */
        $member = $data['member'];

        $this->assertInstanceOf('\BiBundle\Entity\Project', $project);
        $this->assertInstanceOf('\BiBundle\Entity\Member', $member);

        $role = 'new role';
        $projectService = $this->container->get('bi.project.service');
        $projectService->changeMemberRole($member, $role);
        // check new role
        $this->assertEquals($role, $member->getRoleDescription(), 'role description is not changed');
    }

    protected function tearDown()
    {
        if (!$this->hasDependencies()) {
            $q = $this->em->createQuery("DELETE FROM BiBundle\Entity\Member m");
            $q->execute();
            $q = $this->em->createQuery("DELETE FROM BiBundle\Entity\Project p");
            $q->execute();
            $this->em->close();
            parent::tearDown();
        }
    }

    public function testCustomerCanBeDeleted()
    {
        $role = $this->em->getRepository(MemberRole::class)->findOneBy(['code' => MemberRole::ROLE_CUSTOMER]);
        $project = $this->testCreateProject();
        $members = new Member();
        $members->setUser($project->getCustomer())
            ->setProject($project)
            ->setRole($role);
        $this->em->persist($members);
        $this->em->flush();

        $projectService = $this->container->get('bi.project.service');
        $projectService->deleteCustomer($project, $project->getCustomer());
        $members = $this->em->getRepository(Member::class)->findBy([
            'project' => $project,
            'role' => $role,
        ]);
        $this->assertTrue(null == $project->getCustomer());
        $this->assertEmpty($members);
    }

    public function testWatcherCanBeDeleted()
    {
        $data = $this->testAddWatcher();
        $project = $data['project'];
        $member = $data['member'];
        $projectService = $this->container->get('bi.project.service');
        $projectService->deleteWatcher($project, $member->getUser());
        $members = $this->em->getRepository(Member::class)->findBy([
            'project' => $project,
            'role' => $member->getRole(),
        ]);
        $this->assertEmpty($members);
    }
}
