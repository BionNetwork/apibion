<?php

namespace BiBundle\Tests\Service;

use BiBundle\Entity\Activation;
use BiBundle\Entity\ActivationStatus;
use BiBundle\Entity\Card;
use BiBundle\Entity\Dashboard;
use BiBundle\Entity\DashboardActivation;
use BiBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;

class DashboardActivationServiceTest extends KernelTestCase
{
    /**
     * @var Container
     */
    private $container;
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        static::bootKernel();
        $this->container = static::$kernel->getContainer();
        $this->entityManager = $this->container->get('doctrine.orm.entity_manager');
    }


    public function testAddActivationDashboardAction()
    {
        /**
         * @var User $user
         * @var Activation $activation
         * @var Card $card
         */
        $user = $this->container->get('repository.user_repository')->findOneBy([]);
        $card = $this->container->get('repository.card_repository')->findOneBy([]);

        $dashboard = new Dashboard();
        $dashboard->setName('Test dashboard');
        $dashboard->setUser($user);

        $activation = new Activation();
        $activation->setCard($card);
        $activation->setUser($user);
        $activation->setActivationStatus($this->entityManager->getRepository(ActivationStatus::class)
            ->findOneBy(['code' => ActivationStatus::STATUS_ACTIVE]));

        $this->entityManager->persist($dashboard);
        $this->entityManager->persist($activation);
        $this->entityManager->flush();

        $dashboardActivation = $this->container->get('bi.dashboard_activation.service')
            ->addActivationToDashboard($activation, $dashboard);

        $this->assertSame($dashboard, $dashboardActivation->getDashboard());
        $this->assertSame($activation, $dashboardActivation->getActivation());

        return $dashboardActivation;
    }

    /**
     * @depends testAddActivationDashboardAction
     */
    public function testRemoveActivationDashboardAction(DashboardActivation $dashboardActivation)
    {
        $dashboard = $dashboardActivation->getDashboard();
        $activation = $dashboardActivation->getActivation();
        $this->container->get('bi.dashboard_activation.service')->removeActivationFromDashboard($activation, $dashboard);
        $da = $this->entityManager->getRepository(DashboardActivation::class)
            ->findOneBy(['activation' => $activation, 'dashboard' => $dashboard]);

        $this->assertNull($da);
    }
}
