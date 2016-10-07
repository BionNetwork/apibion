<?php

namespace BiBundle\Repository;

use BiBundle\Entity\DashboardActivation;
use BiBundle\Entity\DashboardCard;

/**
 * DashboardActivationRepository
 */
class DashboardActivationRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param DashboardActivation $dashboardActivation
     */
    public function save(DashboardActivation $dashboardActivation)
    {
        $this->getEntityManager()->persist($dashboardActivation);
        $this->getEntityManager()->flush($dashboardActivation);
    }

    /**
     * @param DashboardActivation $dashboardActivation
     */
    public function delete(DashboardActivation $dashboardActivation)
    {
        $this->getEntityManager()->remove($dashboardActivation);
        $this->getEntityManager()->flush($dashboardActivation);
    }
}
