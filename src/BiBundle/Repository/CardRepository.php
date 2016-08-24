<?php

namespace BiBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use BiBundle\Entity\Card;
use Doctrine\ORM\Query\Expr\Join;

/**
 * CardRepository
 */
class CardRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Find cards by filter
     *
     * @param \BiBundle\Entity\Filter\Card $filter
     * @return array
     */
    public function findByFilter(\BiBundle\Entity\Filter\Card $filter)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select([
            'c.id',
            'c.name',
            'c.type',
            'c.createdOn',
        ])
            ->from('BiBundle:Card', 'c')
            ->orderBy('c.createdOn', 'DESC');
        if ($filter->id) {
            $qb->andWhere('c.id = :id');
            $qb->setParameter('id', $filter->id);
        }
        $qb->setMaxResults($filter->getLimit());
        $qb->setFirstResult($filter->getOffset());

        return $qb->getQuery()->getResult();
    }
    
}