<?php

namespace BiBundle\Service;

use BiBundle\BiBundle;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\Exception\ValidatorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ActivationService extends UserAwareService
{

    /**
     * Получение активаций карточек пользователя
     *
     * @param User $user
     */
    public function getUserActivations(\BiBundle\Entity\User $user)
    {
        $em = $this->getEntityManager();
        return $em->getRepository('BiBundle:Activation')->getUserActivations($user);
    }

    /**
     * Возвращает активации по фильтру
     *
     * @param \BiBundle\Entity\Filter\Activation $filter
     *
     * @return \BiBundle\Entity\Activation[]
     */
    public function getByFilter(\BiBundle\Entity\Filter\Activation $filter)
    {
        $em = $this->getEntityManager();
        $items = $em->getRepository('BiBundle:Activation')->getByFilter($filter);

        $resultArray = [];
        foreach ($items as $item) {
            $resultArray[] = $item;
        }
        return $resultArray;
    }

}
