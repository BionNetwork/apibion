<?php

namespace BiBundle\Tests\Service;

use BiBundle\Entity\Activation;
use BiBundle\Entity\Card;
use BiBundle\Entity\Dashboard;
use BiBundle\Entity\DashboardActivation;
use BiBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ActivationControllerTest extends KernelTestCase
{
    public function testAddActivationDashboardAction()
    {
        static::bootKernel();
        $container = static::$kernel->getContainer();

        /**
         * @var User $user
         * @var Activation $activation
         * @var Card $card
         */
        $user = $container->get('repository.user_repository')->find(1);
        $activation = $user->getActivation()->first();
        $card = $container->get('repository.card_repository')->find(1);

        $dashboard = new Dashboard();
        $dashboard->setName('Test dashboard');
        $dashboard->setUser($user);

        $activation = new Activation();
//        $activation->setCard();
//        $activation->setUser();
//        $activation->setActivationStatus();

        $service = $container->get('bi.dashboard_activation.service');
        $dashboardActivation = $service->addActivationToDashboard($activation, $dashboard);

        $this->assertSame($dashboard, $dashboardActivation->getDashboard());
        $this->assertSame($activation, $dashboardActivation->getActivation());

        return $dashboardActivation;
    }

    /**
     * @depends testAddActivationDashboardAction
     */
    public function testRemoveActivationDashboardAction(DashboardActivation $dashboardActivation)
    {

    }
}
