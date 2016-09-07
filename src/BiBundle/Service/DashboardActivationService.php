<?php

namespace BiBundle\Service;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\DashboardActivation;
use BiBundle\Entity\Exception\ValidatorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class DashboardActivationService extends UserAwareService
{

    /**
     * Save or create activation to dashboard link
     *
     * @param DashboardActivation $dashboardActivation
     */
    public function save(DashboardActivation $dashboardActivation)
    {
        $em = $this->getEntityManager();

        if ($dashboardActivation->getId() === null) {
            $dashboardActivation->setUser($this->getUser());
            $dashboardActivation->setCreatedOn(new \DateTime());
        }

        $em->persist($dashboardActivation);
        $em->flush();
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
