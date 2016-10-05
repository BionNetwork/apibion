<?php

namespace BiBundle\Repository;

use BiBundle\Entity\Card;

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
        $qb->select('c')
            ->from('BiBundle:Card', 'c')
            ->orderBy('c.createdOn', 'desc');
        if ($filter->id) {
            $qb->andWhere('c.id = :id');
            $qb->setParameter('id', $filter->id);
        }
        if ($filter->category_id) {
            $qb->andWhere('c.cardCategory = :categoryId')->setParameter('categoryId', $filter->category_id);
        }
        $qb->setMaxResults($filter->getLimit());
        $qb->setFirstResult($filter->getOffset());

        return $qb->getQuery()->getResult();
    }
    
}