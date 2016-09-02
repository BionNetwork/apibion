<?php

namespace BiBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use BiBundle\Entity\Representation;
use Doctrine\ORM\Query\Expr\Join;

/**
 * RepresentationRepository
 */
class RepresentationRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Find representations by filter
     *
     * @param \BiBundle\Entity\Filter\Representation $filter
     * @return array
     */
    public function findByFilter(\BiBundle\Entity\Filter\Representation $filter)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('r')
            ->from('BiBundle:Representation', 'r')
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