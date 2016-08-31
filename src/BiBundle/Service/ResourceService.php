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
 * Dashboard service
 */
class ResourceService extends UserAwareService
{

    /**
     * Save resource
     *
     * @param \BiBundle\Entity\Resource $resource
     */
    public function save(\BiBundle\Entity\Resource $resource)
    {
        $em = $this->getEntityManager();

        if ($resource->getId() === null) {
            $resource->setCreatedOn(new \DateTime());
        }

        $em->persist($resource);
        $em->flush();
        return $resource;
    }
}