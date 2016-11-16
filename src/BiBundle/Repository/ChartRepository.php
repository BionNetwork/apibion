<?php

namespace BiBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use BiBundle\Entity\Chart;
use Doctrine\ORM\Query\Expr\Join;

/**
 * RepresentationRepository
 */
class ChartRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Find charts by filter
     *
     * @param \BiBundle\Entity\Filter\Chart $filter
     * @return array
     */
    public function findByFilter(\BiBundle\Entity\Filter\Chart $filter)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('r')
            ->from('BiBundle:Chart', 'r')
            ->orderBy('r.createdOn', 'desc');
        if ($filter->id) {
            $qb->andWhere('r.id = :id');
            $qb->setParameter('id', $filter->id);
        }
        $qb->setMaxResults($filter->getLimit());
        $qb->setFirstResult($filter->getOffset());

        return $qb->getQuery()->getResult();
    }

}