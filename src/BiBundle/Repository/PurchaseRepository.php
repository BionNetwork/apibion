<?php

namespace BiBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use BiBundle\Entity\User;
use Doctrine\ORM\Query\Expr\Join;

/**
 * PurchaseRepository
 */
class PurchaseRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Получение приобретенных карточек пользователя
     *
     * @param \BiBundle\Entity\User $user
     * @return \BiBundle\Entity\Card[]
     */
    public function getUserCards($user) {

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('c')
            ->from('BiBundle:Card', 'c')
            ->innerJoin('BiBundle:Purchase', 'p', Join::WITH, 'c.id = p.card')
            ->where('p.user = :user_id')
            ->setParameter('user_id', $user->getId());
        $query = $qb->getQuery();

        $cardList = $query->getResult();
        return $cardList;
    }
}
