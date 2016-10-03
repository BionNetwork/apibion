<?php

namespace BiBundle\Service;

use BiBundle\Entity\Activation;
use BiBundle\Entity\Dashboard;
use BiBundle\Entity\DashboardActivation;
use BiBundle\Repository\DashboardActivationRepository;
use Doctrine\ORM\Mapping as ORM;

class DashboardActivationService
{
    /**
     * @var DashboardActivationRepository
     */
    private $dashboardActivationRepository;

    /**
     * Constructor
     *
     * DashboardActivationService constructor.
     * @param DashboardActivationRepository $dashboardActivationRepository
     */
    public function __construct(DashboardActivationRepository $dashboardActivationRepository)
    {
        $this->dashboardActivationRepository = $dashboardActivationRepository;
    }

    /**
     * @param Activation $activation
     * @param Dashboard $dashboard
     * @return DashboardActivation
     * @throws \ErrorException
     */
    public function addActivationToDashboard(Activation $activation, Dashboard $dashboard)
    {
        if ($activation->getUser() !== $dashboard->getUser()) {
            throw new \ErrorException('Dashboard and activation user don\'t match');
        }
        $dashboardActivation = new DashboardActivation();
        $dashboardActivation->setActivation($activation);
        $dashboardActivation->setDashboard($dashboard);
        $dashboardActivation->setUser($activation->getUser());
        $this->dashboardActivationRepository->save($dashboardActivation);

        return $dashboardActivation;
    }

    /**
     * @param Activation $activation
     * @param Dashboard $dashboard
     * @throws \ErrorException
     */
    public function removeActivationFromDashboard(Activation $activation, Dashboard $dashboard)
    {
        if ($activation->getUser() !== $dashboard->getUser()) {
            throw new \ErrorException('Dashboard and activation user don\'t match');
        }
        /** @var DashboardActivation $dashboardActivation */
        $dashboardActivation = $this->dashboardActivationRepository
            ->findOneBy([
                'activation' => $activation,
                'dashboard' => $dashboard
            ]);
        if (!$dashboardActivation) {
            throw new \ErrorException();
        }
        $this->dashboardActivationRepository->delete($dashboardActivation);
    }

    /**
     * Возвращает список карточек по фильтру
     *
     * @param \BiBundle\Entity\Filter\DashboardActivation $filter
     *
     * @return \BiBundle\Entity\DashboardActivation[]
     */
    public function getByFilter(\BiBundle\Entity\Filter\DashboardActivation $filter)
    {
        $em = $this->getEntityManager();
        $items = $em->getRepository('BiBundle:DashboardActivation')->findByFilter($filter);

        // Развернем в структуру
        $resultArray = [];
        foreach ($items as $row) {
            $resultArray[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'type' => $row['type'],

            ];
        }
        return $resultArray;
    }



}
