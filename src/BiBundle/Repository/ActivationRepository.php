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

    /**
     * Получение активаций по фильтру
     *
     * @param \BiBundle\Entity\Filter\Activation $filter
     * @return array
     */
    public function getByFilter($filter) {

        $qb = $this->getEntityManager()->createQueryBuilder();
        file_put_contents('/tmp/eee.txt', print_r($filter, 1));
        $qb->select('a')
            ->from('BiBundle:Activation', 'a')
            ->innerJoin('BiBundle:Card', 'c', Join::WITH, 'c.id = a.card');
        if($filter->user_id) {
            $qb->andWhere('a.user = :user_id');
            $qb->setParameter('user_id', $filter->user_id);
        }
        if($filter->card_id) {
            $qb->andWhere('a.card = :card_id');
            $qb->setParameter('card_id', $filter->card_id);
        }
        if($filter->dashboard_id) {
            $qb->innerJoin('BiBundle:DashboardActivation', 'da', Join::WITH, 'da.activation = a.id');
            $qb->andWhere('da.dashboard = :dashboard_id');
            $qb->setParameter('dashboard_id', $filter->dashboard_id);
        }
        if($filter->activation_status) {
            $qb->innerJoin('BiBundle:ActivationStatus', 'ass', Join::WITH, 'ass.id = a.activation_status');
            $qb->andWhere('ass.code = :activation_status');
            $qb->setParameter('activation_status', $filter->activation_status);
        }
        $query = $qb->getQuery();

        $cardList = $query->getResult();
        return $cardList;
    }
}
