<?php

namespace BiBundle\Service;

use BiBundle\Entity\Card;

class CardService extends UserAwareService
{

    /**
     * Returns cards by filter
     *
     * @param \BiBundle\Entity\Filter\Card $filter
     *
     * @return \BiBundle\Entity\Card[]
     */
    public function getByFilter(\BiBundle\Entity\Filter\Card $filter)
    {
        $em = $this->getEntityManager();
        return $em->getRepository('BiBundle:Card')->findByFilter($filter);
    }

    /**
     * Returns all cards with data from related tables
     *
     * @return \BiBundle\Entity\Card[]
     */
    public function getAllCards()
    {
        $em = $this->getEntityManager();
        return $em->getRepository('BiBundle:Card')->findAllCards();
    }
}
