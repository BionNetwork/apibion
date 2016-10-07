<?php

namespace BiBundle\Repository;

use BiBundle\Entity\Filter\CardCategory;
use Doctrine\ORM\EntityRepository;

/**
 * CardCategoryRepository
 */
class CardCategoryRepository extends EntityRepository
{
    /**
     * Find categories by filter
     *
     * @param CardCategory $filter
     * @return array
     */
    public function findByFilter(CardCategory $filter)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('cc')
            ->from('BiBundle:CardCategory', 'cc');
        if ($filter->id) {
            $qb->andWhere('cc.id = :id');
            $qb->setParameter('id', $filter->id);
        }
        $qb->setMaxResults($filter->getLimit());
        $qb->setFirstResult($filter->getOffset());

        return $qb->getQuery()->getResult();
    }
}
