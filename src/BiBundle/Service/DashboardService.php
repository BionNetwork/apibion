<?php

/**
 * @package    BiBundle\Service
 */

namespace BiBundle\Service;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\Dashboard;
use BiBundle\Entity\Exception\ValidatorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Dashboard service
 */
class DashboardService extends UserAwareService
{
    /**
     * Save dashboard
     *
     * @param Dashboard $dashboard
     */
    public function save(Dashboard $dashboard)
    {
        $em = $this->getEntityManager();

        if ($dashboard->getId() === null) {
            $dashboard->setUser($this->getUser());
            $dashboard->setCreatedOn(new \DateTime());
        }

        $em->persist($dashboard);
        $em->flush();
    }
}