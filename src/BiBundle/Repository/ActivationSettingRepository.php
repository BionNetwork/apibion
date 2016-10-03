<?php

namespace BiBundle\Repository;

use BiBundle\Entity\Activation;
use BiBundle\Entity\ActivationSetting;
use BiBundle\Service\Exception\ActivationSettingException;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\Intl\Exception\NotImplementedException;

/**
 * ActivationSettingRepository
 *
 */
class ActivationSettingRepository extends \Doctrine\ORM\EntityRepository
{
    public function save(ActivationSetting $activationSetting)
    {
        if ($this->_em->getUnitOfWork()->getEntityState($activationSetting) === UnitOfWork::STATE_NEW) {
            $this->_em->persist($activationSetting);
        }
        $this->_em->flush($activationSetting);
    }

    /**
     * Get latest active entity for given key
     *
     * @param Activation $activation
     * @param $key
     * @return ActivationSetting|null|object
     */
    public function getLatestActualByKey(Activation $activation, $key)
    {
        return $this->findOneBy(
            [
                'activation' => $activation,
                'key' => $key,
                'deletedOn' => null,
            ],
            ['createdOn' => 'desc']
        );
    }

    /**
     * @param Activation $activation
     * @param $key
     * @return ActivationSetting[]
     */
    public function getActualByKey(Activation $activation, $key)
    {
        return $this->findBy(
            [
                'activation' => $activation,
                'key' => $key,
                'deletedOn' => null,
            ],
            ['createdOn' => 'desc']
        );
    }

    /**
     * @param Activation $activation
     * @param $key
     * @return ActivationSetting|null
     */
    public function getRedoElementByKey(Activation $activation, $key)
    {
        return $this->createQueryBuilder('as')
            ->delete('as')
            ->where('as.key = :key')
            ->andWhere('as.activation = :activation')
            ->andWhere('as.deletedOn IS NOT NULL')
            ->orderBy('ad.deletedOn', 'desc')
            ->setParameter('activation', $activation)
            ->setParameter('key', $key)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @param Activation $activation
     * @param $key
     * @return bool
     */
    public function keyExists(Activation $activation, $key)
    {
        return $this->createQueryBuilder('as')
            ->delete('as')
            ->where('as.key = :key')
            ->andWhere('as.activation = :activation')
            ->setParameter('activation', $activation)
            ->setParameter('key', $key)
            ->select('COUNT as')
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    /**
     * Delete all records for given key
     *
     * @param Activation $activation
     * @param $key
     */
    public function deleteByKey(Activation $activation, $key)
    {
        $this->createQueryBuilder('as')
            ->delete('as')
            ->where('as.key = :key')
            ->andWhere('as.activation = :activation')
            ->setParameter('activation', $activation)
            ->setParameter('key', $key)
            ->getQuery()
            ->execute();
    }

    public function softDelete(ActivationSetting $activationSetting)
    {
        if ($activationSetting->getDeletedOn() !== null) {
            throw new ActivationSettingException("ActivationSetting {$activationSetting->getId()} is already soft deleted");
        }
        $activationSetting->setDeletedOn(new \DateTime());
        $this->_em->flush($activationSetting);
    }

    public function clearSoftDelete(ActivationSetting $activationSetting)
    {
        if ($activationSetting->getDeletedOn() === null) {
            throw new ActivationSettingException("ActivationSetting {$activationSetting->getId()} is not soft deleted");
        }
        $activationSetting->setDeletedOn(null);
        $this->_em->flush($activationSetting);
    }

    public function purgeSoftDeletes($activation, $key)
    {
        $this->createQueryBuilder('as')
            ->delete('as')
            ->where('as.key = :key')
            ->andWhere('as.activation = :activation')
            ->andWhere('ad.deletedOn IS NOT NULL')
            ->setParameter('activation', $activation)
            ->setParameter('key', $key)
            ->getQuery()
            ->execute();
    }

    public function getLatestActualForAll(Activation $activation)
    {
        throw new NotImplementedException('');
    }
}
