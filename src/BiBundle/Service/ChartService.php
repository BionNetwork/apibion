<?php

namespace BiBundle\Service;

use Doctrine\ORM\Query;
use BiBundle\Entity\Chart;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ChartService extends UserAwareService
{

    /**
     * Возвращает графики по фильтру
     *
     * @param \BiBundle\Entity\Filter\Chart $filter
     *
     * @return \BiBundle\Entity\Chart[]
     */
    public function getByFilter(\BiBundle\Entity\Filter\Chart $filter)
    {
        $em = $this->getEntityManager();
        return $em->getRepository('BiBundle:Chart')->findByFilter($filter);
    }

}
