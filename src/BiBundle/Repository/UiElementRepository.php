<?php

namespace BiBundle\Repository;

use BiBundle\Entity\UiElement;

/**
 * UiElementRepository
 */
class UiElementRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Find all UI elements structured in a tree
     *
     * @return UiElement[]
     */
    public function findElementsTree()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder()
            ->select('t, c1, c2')
            ->from('BiBundle:UiElement', 't')
            ->leftJoin('t.children', 'c1')
            ->leftJoin('c1.children', 'c2')
            ->where('t.parent IS NULL');

        return $qb->getQuery()->getResult();
    }
}
