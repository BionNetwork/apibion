<?php

namespace BiBundle\Service;

use Doctrine\ORM\Query;
use BiBundle\Entity\Representation;
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
