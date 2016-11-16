<?php

namespace BiBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use BiBundle\Entity\Chart;
use Doctrine\ORM\Query\Expr\Join;

/**
 * RepresentationRepository
 */
class ResourceRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Find representations by filter
     *
     * @param \BiBundle\Entity\Filter\Resource $filter
     * @return array
     */
    public function findByFilter(\BiBundle\Entity\Filter\Resource $filter)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('r')
            ->from('BiBundle:Resource', 'r')
            ->orderBy('r.createdOn', 'desc');
        if ($filter->id) {
            $qb->andWhere('r.id = :id');
            $qb->setParameter('id', $filter->id);
        }
        if ($filter->user_id) {
            $qb->andWhere('r.user = :user_id');
            $qb->setParameter('user_id', $filter->user_id);
        }
        if ($filter->activation_id) {
            $qb->andWhere('r.activation = :activation_id');
            $qb->setParameter('activation_id', $filter->activation_id);
        }
        $qb->setMaxResults($filter->getLimit());
        $qb->setFirstResult($filter->getOffset());

        return $qb->getQuery()->getResult();
    }

}