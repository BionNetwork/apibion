<?php

namespace BiBundle\Service;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\DashboardCard;
use BiBundle\Entity\Exception\ValidatorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class DashboardCardService extends UserAwareService
{

    /**
     * Save dashboard card
     *
     * @param DashboardCard $dashboardCard
     */
    public function save(DashboardCard $dashboardCard)
    {
        $em = $this->getEntityManager();

        if ($dashboardCard->getId() === null) {
            $dashboardCard->setUser($this->getUser());
            $dashboardCard->setCreatedOn(new \DateTime());
        }

        $em->persist($dashboardCard);
        $em->flush();
    }

    /**
     * Возвращает список карточек по фильтру
     *
     * @param \BiBundle\Entity\Filter\DashboardCard $filter
     *
     * @return \BiBundle\Entity\DashboardCard[]
     */
    public function getByFilter(\BiBundle\Entity\Filter\DashboardCard $filter)
    {
        $em = $this->getEntityManager();
        $items = $em->getRepository('BiBundle:DashboardCard')->findByFilter($filter);

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
