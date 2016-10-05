<?php

namespace BiBundle\Service;

use BiBundle\Entity\Card;

class CardService extends UserAwareService
{

    /**
     * Возвращает проект по фильтру
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
     * Возвращает проект по фильтру
     *
     * @return \BiBundle\Entity\Card[]
     */
    public function getAllCards()
    {
        $em = $this->getEntityManager();
        return $em->getRepository('BiBundle:Card')->findAll();
    }
}
