<?php

namespace BiBundle\Service;

use BiBundle\Service\Exception\UserCard\AlreadyPurchasedException;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\Card;
use BiBundle\Entity\Exception\ValidatorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

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

}
