<?php

/**
 * @package    BiBundle\Service
 */

namespace BiBundle\Service;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManager;
use BiBundle\Entity\Exception\ValidatorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * ArgumentBond service
 */
class ArgumentBondService extends UserAwareService
{

    /**
     * Возвращает рабочие столы по фильтру
     *
     * @param \BiBundle\Entity\Activation $activation
     *
     * @return \BiBundle\Entity\ArgumentBond[]
     */
    public function getArgumentBondList(\BiBundle\Entity\Activation $activation)
    {
        $em = $this->getEntityManager();
        return $em->getRepository('BiBundle:ArgumentBond')->findBy(['activation' => $activation]);
    }

    /**
     * Save or update argument bond
     *
     * @param Dashboard $dashboard
     */
    public function save(\BiBundle\Entity\ArgumentBond $argumentBond)
    {
        $em = $this->getEntityManager();

        if ($argumentBond->getId() === null) {
            $filter = [
                'activation' => $argumentBond->getActivation(),
                'resource' => $argumentBond->getResource(),
                'argument' => $argumentBond->getArgument()
            ];
            $exArgumentBond = $em->getRepository('BiBundle:ArgumentBond')->findOneBy($filter);

            if($exArgumentBond) {
                $exArgumentBond->setTableName($argumentBond->getTableName());
                $exArgumentBond->setColumnName($argumentBond->getColumnName());
                $argumentBond = $exArgumentBond;
            }
        }

        $em->persist($argumentBond);
        $em->flush();

        return $argumentBond;
    }
}