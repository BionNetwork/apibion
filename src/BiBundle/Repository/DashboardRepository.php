<?php

namespace BiBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use BiBundle\Entity\Dashboard;
use Doctrine\ORM\Query\Expr\Join;

/**
 * DashboardRepository
 */
class DashboardRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Find dashboards by filter
     *
     * @param \BiBundle\Entity\Filter\Dashboard $filter
     * @return array
     */
    public function getByFilter(\BiBundle\Entity\Filter\Card $filter)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select([
            'd.id',
            'd.name',
            'd.type',
            'd.createdOn',
        ])
            ->from('BiBundle:Dashboard', 'd')
            ->orderBy('d.createdOn', 'DESC');
        if ($filter->id) {
            $qb->andWhere('d.id = :id');
            $qb->setParameter('id', $filter->id);
        }
        if ($filter->user_id) {
            $qb->andWhere('d.user_id = :user_id');
            $qb->setParameter('user_id', $filter->user_id);
        }
        $qb->setMaxResults($filter->getLimit());
        $qb->setFirstResult($filter->getOffset());

        return $qb->getQuery()->getResult();
    }
}
