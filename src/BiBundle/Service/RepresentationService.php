<?php

namespace BiBundle\Service;

use BiBundle\BiBundle;
use BiBundle\Service\Exception\Purchase\AlreadyPurchasedException;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\Representation;
use BiBundle\Entity\Exception\ValidatorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class RepresentationService extends UserAwareService
{

    /**
     * Возвращает представления по фильтру
     *
     * @param \BiBundle\Entity\Filter\Representation $filter
     *
     * @return \BiBundle\Entity\Representation[]
     */
    public function getByFilter(\BiBundle\Entity\Filter\Representation $filter)
    {
        $em = $this->getEntityManager();
        return $em->getRepository('BiBundle:Representation')->findByFilter($filter);
    }

}
