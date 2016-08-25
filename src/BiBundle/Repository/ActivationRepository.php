<?php

namespace BiBundle\Repository;

use Doctrine\ORM\AbstractQuery;
use BiBundle\Entity\User;
use Doctrine\ORM\Query\Expr\Join;

/**
 * PurchaseRepository
 */
class ActivationRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Получение активаций карточек пользователя
     *
     * @param \BiBundle\Entity\User $user
     * @return array
     */
    public function getUserActivations($user) {

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select([
            'c.id as card_id',
            'a.user_id as user_id'
        ])
            ->from('BiBundle:Card', 'c')
            ->innerJoin('BiBundle:Activation', 'a', Join::WITH, 'c.id = a.card')
            ->where('a.user = :user_id')
            ->setParameter('user_id', $user->getId());
        $query = $qb->getQuery();

        $cardList = $query->getResult();
        return $cardList;
    }
}
