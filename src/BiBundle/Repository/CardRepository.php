<?php

namespace BiBundle\Repository;

use BiBundle\Entity\Card;

/**
 * CardRepository
 */
class CardRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Finds cards by filter
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
            'c.description',
            'c.description_long',
            'c.rating',
            'c.price',
            'c.carousel',
            'c.createdOn AS created_on',
            'c.updatedOn AS updated_on',
            'cc.id AS category_id',
            'p.id AS purchase_id'
        ])
            ->from('BiBundle:Card', 'c')
            ->leftJoin('c.cardCategory', 'cc')
            ->leftJoin('BiBundle:Purchase', 'p', 'WITH', 'p.card = c.id AND p.user = ' . $filter->user_id)
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

    /**
     * Finds all cards with data from related tables
     *
     * @return array
     */
    public function findAllCards()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('c', 'cc', 'p', 'a', 'cp')
            ->from('BiBundle:Card', 'c')
            ->leftJoin('c.cardCategory', 'cc')
            ->leftJoin('c.purchase', 'p')
            ->leftJoin('c.argument', 'a')
            ->leftJoin('c.cardRepresentation', 'cp')
            ->orderBy('c.createdOn', 'desc');

        return $qb->getQuery()->getResult();
    }

}